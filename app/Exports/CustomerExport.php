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
        return Customer::query()->with('company')->orderBy('updated_at', 'desc');
    }

    /**
     * @var Customer $customer
     */
    public function map($customer): array
    {
        return [
            [
                $customer->first_name . ' ' . $customer->last_name,
                $customer->age ?? '',
                $customer->genderToText(),
                $customer->birthday ? $customer->birthday->format('d/m/Y') : '',
                $customer->address ?? '',
                $customer->typeToText(),
                $customer->company ? $customer->company->name : '',
                $customer->company ? $customer->company->address : '',
                $customer->created_at ? $customer->created_at->format('d/m/Y H:i:s') : '',
                Utils::actionUser($customer->created_by),
                $customer->updated_at ? $customer->updated_at->format('d/m/Y H:i:s') : '',
                Utils::actionUser($customer->updated_by),
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
            'Work Address',
            'Created At',
            'Created By',
            'Updated At',
            'Updated By',
        ];
    }
}
