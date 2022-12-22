<?php

namespace App\Services;

use App\Exports\ErrorWhenImport;
use App\Helpers\Utils;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
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
                if ($order->customer) {
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
            ->editColumn('total', function ($order) {
                return number_format($order->total) . ' VND';
            })
            ->editColumn('order_date', function ($order) {
                return Utils::formatDate($order->order_date);
            })
            ->editColumn('created_by', function ($order) {
                return Utils::actionUser($order->created_by);
            })
            ->editColumn('updated_by', function ($order) {
                return Utils::actionUser($order->updated_by);
            })
            ->rawColumns(['action'])
            ->filter(function ($query) {
                if (request()->has('search_customer')) {
                    if (request('search_customer')) {
                        $query->where('customer_id', '=', request('search_customer'));
                    }
                }

                if (request()->has('search_total_from')) {
                    $from = request('search_total_from');
                    if ($from) {
                        $query->where('total', '>=', intval($from));
                    }
                }
                if (request()->has('search_total_to')) {
                    $to = request('search_total_to');
                    if ($to) {
                        $query->where('total', '<=', intval($to));
                    }
                }

                if (request()->has('search_date_from')) {
                    $from = request('search_date_from');
                    if ($from) {
                        $query->where('order_date', '>=', (DateTime::createFromFormat('d/m/Y', $from))->format('Y-m-d'));
                    }
                }
                if (request()->has('search_date_to')) {
                    $to = request('search_date_to');
                    if ($to) {
                        $query->where('order_date', '<=', (DateTime::createFromFormat('d/m/Y', $to))->format('Y-m-d'));
                    }
                }
            })
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
     * Get customer list
     *
     * @return Customer[]
     */
    public function getCustomerList()
    {
        $me = Auth::user();
        if ($me->isAdmin()) {
            $customers = Customer::all();
        } else {
            $customers = Customer::with('beApplied')->where([
                ['user_ids', 'all', [$me->id]]
            ])->get();
        }
        return $customers;
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
