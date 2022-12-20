<?php

namespace App\Services;

use App\Exports\ErrorWhenImport;
use App\Helpers\Utils;
use App\Models\Company;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class OrderService
{
    /**
     * store order with data
     *
     * @param  array $data
     * @return bool true: created | false: error
     */
    public function store($data)
    {
        try {
            if (isset($data['_id'])) {
                $id = $data['_id'];
                unset($data['_id']);
                Order::where('_id', $id)->update($data);
            } else {
                Order::create($data);
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
        return Order::findOrFail($id);
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
            // update order
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
                return $order->customer->first_name . ' ' . $order->customer->first_name;
            })
            ->addColumn('action', function ($order) {
                return Utils::renderActionHtml(
                    route('orders.detail', ['id' => $order->id]),
                    route('orders.delete', ['id' => $order->id]),
                    'confirmDeleteOrder(this)'
                );
            })
            ->editColumn('order_date', function ($order) {
                return $order->formatDate($order->order_date);
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
}
