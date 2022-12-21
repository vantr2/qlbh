<?php

namespace App\Services;

use App\Exports\ErrorWhenImport;
use App\Helpers\Utils;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

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
            return true;
        } catch (Exception $ex) {
            Log::error('deleteCustomer: ' . $ex);
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
        $me = Auth::user();
        if ($me->isAdmin()) {
            $query = Customer::query()->with('company');
        } else {
            $query = Customer::query()->where([
                ['user_ids', 'all', [Auth::user()->id]]
            ])->with('company', 'beApplied');
        }

        return DataTables::of($query)
            ->addColumn('name', function ($customer) {
                return $customer->first_name . ' ' . $customer->last_name;
            })
            ->addColumn('workplace', function ($customer) {
                if ($customer->company) {
                    return $customer->company->name;
                }
                return null;
            })
            ->addColumn('action', function ($customer) {
                return Utils::renderActionHtml(
                    route('customers.detail', ['id' => $customer->id]),
                    route('customers.delete', ['id' => $customer->id]),
                    'confirmDeleteCustomer(this)'
                );
            })
            ->editColumn('age', function ($customer) {
                return $customer->age ?? '';
            })
            ->editColumn('gender', function ($customer) {
                return $customer->genderToText();
            })
            ->editColumn('birthday', function ($customer) {
                return $customer->birthday ? Utils::formatDate($customer->birthday) : '';
            })
            ->editColumn('type', function ($customer) {
                return $customer->typeToText();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Export error file after import customer by excel file
     *
     * @param  array $failures
     * @return string csv file name
     */
    public function exportErrorFile($failures)
    {
        $fileNameFormat = config('excel.imports.error.file_name_format.customer');
        $fileName = sprintf($fileNameFormat, date('YmdHis'));

        $filePath = config('excel.imports.error.path');

        (new ErrorWhenImport($failures))->store($filePath . '/' . $fileName);

        return $fileName;
    }
}
