<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Rules\CustomerExists;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrderImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * import customer from excel to database via collection
     *
     * @param  Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        $authUserName = Auth::user()->name;

        foreach ($rows as $row) {
            $order = Order::create([
                'customer_id' => $row['customer_id'] ?: null,
                'order_date' => date('Y-m-d', strtotime($row['order_date'])),
                'created_by' => $authUserName,
                'updated_by' => $authUserName,
            ]);

            $total = 0;
            for ($productIndex = 2;; $productIndex++) {
                if ($productIndex > Order::IMPORT_MAX_PRODUCT) {
                    break;
                }

                // product data as format: <name>;<price>;<quantity>
                $key = strval($productIndex);
                $productInfo = null;
                if ($row->has($key)) {
                    $productInfo = explode(';', $row[$key]);
                }
                if ($productInfo && count($productInfo) === 3) {
                    $product = Product::firstOrCreate([
                        'name' => $productInfo[0],
                        'price' => $productInfo[1],
                    ]);
                    $product->created_by = $authUserName;
                    $product->updated_by = $authUserName;
                    $product->save();

                    $amount = intval($productInfo[1]) * intval($productInfo[2]);
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_qty' => $productInfo[2],
                        'product_price' => $productInfo[1],
                        'product_amount' => $amount,
                    ]);

                    $total += $amount;
                }
            }

            // update total for order
            $order->total = $total;
            $order->save();
        }
    }

    public function rules(): array
    {
        return [
            '*.customer_id' => ['required'],
            '*.order_date' => ['required', 'date'],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '*.customer_id.gt' => 'This customer does not exist.',
        ];
    }

    public function prepareForValidation($data, $index)
    {
        //  Handle birthday field
        if (is_int($data['order_date']) || is_float($data['order_date'])) {
            $data['order_date'] = Date::excelToDateTimeObject($data['order_date'])->format('Y-m-d');
        }

        // Handle customer field
        $customerId = 0;
        $customerName = trim($data['customer']);
        Customer::select('_id', 'first_name', 'last_name')->get()->each(function ($customer) use ($customerName, &$customerId) {
            $name = $customer->first_name . ' ' . $customer->last_name;
            if ($name == $customerName) {
                $customerId = $customer->id;
                return false;
            }
        });
        $data['customer_id'] = $customerId;

        return $data;
    }
}
