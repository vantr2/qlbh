<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    /**
     * store customer with data
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
                Customer::where('_id', $id)->update($data);
            } else {
                Customer::create($data);
            }
            return true;
        } catch (Exception $ex) {
            Log::error('storeCustomer: ' . $ex);
        }
        return false;
    }

    /**
     * Get customer detail
     *
     * @param  string $id
     * @return Customer
     */
    public function getDetail($id)
    {
        return Customer::findOrFail($id);
    }

    /**
     * Delete customer by id
     *
     * @param  string $id
     * @return bool true: deleted | false: error
     */
    public function delete($id)
    {
        try {
            Customer::where('_id', $id)->delete();
            // update order
            return true;
        } catch (Exception $ex) {
            Log::error('deleteCustomer: ' . $ex);
        }
        return false;
    }
}
