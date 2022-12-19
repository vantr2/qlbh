<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Validators\Failure;

class ErrorWhenImport implements FromArray, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;
    /**
     * data
     *
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * @param  Failure  $failure
     * @return array
     */
    public function map($failure): array
    {
        return [
            [
                $failure->row(),
                $failure->attribute(),
                $failure->errors()[0],
            ]
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Line',
            'Field',
            'Error Message',
        ];
    }
}
