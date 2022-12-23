<?php

namespace App\Exports;

use App\Helpers\Utils;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Order::query()->with('customer')->orderBy('updated_at', 'desc');
    }

    /**
     * @var Order $order
     */
    public function map($order): array
    {
        return [
            [
                $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : '',
                $order->total ?? 0,
                $order->order_date ?? '',
                $order->created_at->format('d/m/Y H:i:s'),
                Utils::actionUser($order->created_by),
                $order->updated_at->format('d/m/Y H:i:s'),
                Utils::actionUser($order->updated_by),
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Customer Name',
            'Total Money',
            'Order Date',
            'Created At',
            'Created By',
            'Updated At',
            'Updated By',
        ];
    }
}
