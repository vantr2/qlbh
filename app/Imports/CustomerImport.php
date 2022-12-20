<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CustomerImport implements ToCollection, WithHeadingRow, WithValidation
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
            Customer::create([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'age' => $row['age'],
                'gender' => $row['gender'],
                'address' => $row['address'],
                'birthday' => date('Y-m-d', strtotime($row['birthday'])),
                'type' => $row['type'],
                'company_id' => $row['company_id'],
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.first_name' => ['required'],
            '*.last_name' => ['required'],
            '*.age' => ['required', 'numeric', 'min:0'],
            '*.gender' => [
                'required',
                Rule::in([Customer::MALE, Customer::FEMALE, Customer::OTHER])
            ],
            '*.birthday' => ['required', 'date', 'before:today'],
            '*.type' => [
                'required',
                Rule::in([Customer::VIP, Customer::NORMAL])
            ],
            '*.company_id' => ['required', 'exists:companies,_id'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['birthday'] = Date::excelToDateTimeObject($data['birthday'])->format('Y-m-d');
        return $data;
    }
}
