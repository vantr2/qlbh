<?php

namespace App\Imports;

use App\Models\Company;
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
        $authUserId = Auth::user()->id;

        foreach ($rows as $row) {
            $company = Company::firstOrCreate([
                'name' => $row['workplace'],
                'address' => $row['work_address'],
            ]);

            $company->created_by = $authUserId;
            $company->updated_by = $authUserId;
            $company->save();

            Customer::create([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'age' => $row['age'] ?? null,
                'gender' => $row['gender'] ?? null,
                'address' => $row['address'] ?? null,
                'birthday' => $row['birthday'] ? date('Y-m-d', strtotime($row['birthday'])) : null,
                'type' => $row['type'] ?? null,
                'company_id' => $company->id,
                'created_by' => $authUserId,
                'updated_by' => $authUserId,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.first_name' => ['required'],
            '*.last_name' => ['required'],
            '*.age' => ['nullable', 'numeric', 'min:0'],
            '*.gender' => [
                'nullable',
                Rule::in([Customer::MALE, Customer::FEMALE, Customer::OTHER])
            ],
            '*.birthday' => ['nullable', 'date', 'before:today'],
            '*.type' => [
                'nullable',
                Rule::in([Customer::VIP, Customer::NORMAL])
            ],
            '*.workplace' => ['required', 'max:100'],
            '*.work_address' => ['required', 'max:200'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        if (is_int($data['birthday']) || is_float($data['birthday'])) {
            $data['birthday'] = Date::excelToDateTimeObject($data['birthday'])->format('Y-m-d');
        }
        return $data;
    }
}
