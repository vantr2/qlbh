<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Order;
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
        foreach ($rows as $row) {
            Log::debug($row);
            Order::create([
                'customer_id' => $row['customer'],
                'order_date' => date('Y-m-d', strtotime($row['order_date'])),
                'total' => $row['total'],
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.customer' => ['required', 'exists:customers,_id'],
            '*.order_date' => ['required', 'date'],
            '*.total' => [
                'required', Rule::in([0])
            ],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['order_date'] = Date::excelToDateTimeObject($data['order_date'])->format('Y-m-d');
        return $data;
    }
}
