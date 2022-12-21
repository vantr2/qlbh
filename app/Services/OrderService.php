<?php

namespace App\Services;

use App\Exports\ErrorWhenImport;
use App\Helpers\Utils;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class OrderService
{
    /**
     * store order with data
     *
     * @param  array $orderData
     * @param  array $detailData
     * @return bool true: created | false: error
     */
    public function store($orderData, $detailData)
    {
        try {
            if (isset($orderData['_id'])) {
                $orderId = $orderData['_id'];
                unset($orderData['_id']);
                Order::where('_id', $orderId)->update($orderData);
            } else {
                $order = Order::create($orderData);
                $orderId = $order->id;
            }

            foreach ($detailData as $item) {
                OrderDetail::updateOrCreate(
                    ['order_id' => $orderId, 'product_id' => $item['product_id']],
                    [
                        'product_qty' => $item['product_qty'],
                        'product_amount' => $item['product_amount'],
                        'product_price' => $item['product_price'],
                    ]
                );
            }
            return true;
        } catch (Exception $ex) {
            Log::error('storeOrder: ' . $ex);
        }
        return false;
    }

    /**
     * Get order detail
     *
     * @param  string $id
     * @return Order
     */
    public function getDetail($id)
    {
        $order = Order::with('details', 'details.product', 'customer')->findOrFail($id);
        $order->detail_data = $this->constructHiddenDetail($order->details);
        return $order;
    }

    /**
     * Delete order by id
     *
     * @param  string $id
     * @return bool true: deleted | false: error
     */
    public function delete($id)
    {
        try {
            Order::where('_id', $id)->delete();
            return true;
        } catch (Exception $ex) {
            Log::error('deleteOrder: ' . $ex);
        }
        return false;
    }

    /**
     * Build data for datatable with server side processing
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function buildData()
    {
        return DataTables::of(Order::query()->with('customer'))
            ->addColumn('customer_name', function ($order) {
                if($order->customer){
                    return $order->customer->first_name . ' ' . $order->customer->last_name;
                }
                return null;
            })
            ->addColumn('action', function ($order) {
                return Utils::renderActionHtml(
                    route('orders.detail', ['id' => $order->id]),
                    route('orders.delete', ['id' => $order->id]),
                    'confirmDeleteOrder(this)'
                );
            })
            ->editColumn('order_date', function ($order) {
                return Utils::formatDate($order->order_date);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Export error file after import order by excel file
     *
     * @param  array $failures
     * @return string csv file name
     */
    public function exportErrorFile($failures)
    {
        $fileNameFormat = config('excel.imports.error.file_name_format.order');
        $fileName = sprintf($fileNameFormat, date('YmdHis'));

        $filePath = config('excel.imports.error.path');

        (new ErrorWhenImport($failures))->store($filePath . '/' . $fileName);

        return $fileName;
    }

    /**
     * Construct hidden detail
     *
     * @param  OrderDetails $details
     * @return string
     */
    private function constructHiddenDetail($details)
    {
        $hiddenData = [];
        foreach ($details as $detail) {
            $item = [
                'id' => $detail->product_id,
                'name' => $detail->product->name ?? __('Deleted product'),
                'price' => $detail->product_price,
                'quantity' => $detail->product_qty,
                'amount' => $detail->product_amount,
            ];
            $hiddenData[] = $item;
        }
        return json_encode($hiddenData);
    }
}
