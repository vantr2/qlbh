<?php

namespace App\Exports;

use App\Helpers\Utils;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Customer::query()->with('company');
    }

    /**
     * @var Customer $customer
     */
    public function map($customer): array
    {
        return [
            [
                $customer->first_name . ' ' . $customer->last_name,
                $customer->age,
                $customer->genderToText(),
                Utils::formatDate($customer->birthday),
                $customer->address,
                $customer->typeToText(),
                $customer->company ? $customer->company->name : '',
                Utils::formatDate($customer->created_at),
                $customer->created_by,
                Utils::formatDate($customer->updated_at),
                $customer->updated_by,
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Age',
            'Gender',
            'Birthday',
            'Address',
            'Type',
            'Workplace',
            'Created At',
            'Created By',
            'Updated At',
            'Updated By',
        ];
    }
}
