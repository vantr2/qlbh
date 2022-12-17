<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyService
{
    /**
     * Get company list
     *
     * @return Company[]
     */
    public function getCompanyList()
    {
        return Company::all();
    }

    /**
     * store company with data
     *
     * @param  array $data
     * @return bool true: created | false: error
     */
    public function storeCompany($data)
    {
        try {
            if (isset($data['_id'])) {
                $id = $data['_id'];
                unset($data['_id']);
                Company::where('_id', $id)->update($data);
            } else {
                Company::create($data);
            }
            return true;
        } catch (Exception $ex) {
            Log::error('storeCompany: ' . $ex);
        }
        return false;
    }

    /**
     * Get company detail
     *
     * @param  string $id
     * @return Company
     */
    public function getCompanyDetail($id)
    {
        return Company::findOrFail($id);
    }

    /**
     * Delete company by id
     *
     * @param  string $id
     * @return bool true: deleted | false: error
     */
    public function deleteCompany($id)
    {
        try {
            Company::where('_id', $id)->delete();
            Customer::where('company_id', $id)->update(['company_id', null]);
            return true;
        } catch (Exception $ex) {
            Log::error('deleteCompany: ' . $ex);
        }
        return false;
    }
}
