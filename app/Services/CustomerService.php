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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * Get customer detail and order list of this customer
     *
     * @param  string $id
     * @return Customer
     * @throws NotFoundHttpException
     */
    public function getDetail($id)
    {
        return Customer::with('company', 'orders')->findOrFail($id);
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
                    'confirmDeleteCustomer(this)',
                    route('customers.view', ['id' => $customer->id])
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
            ->editColumn('created_by', function ($customer) {
                return Utils::actionUser($customer->created_by);
            })
            ->editColumn('updated_by', function ($customer) {
                return Utils::actionUser($customer->updated_by);
            })
            ->filter(function ($query) {
                if (request()->has('search_name')) {
                    $keyword = request('search_name');
                    if ($keyword) {
                        $query->where(function ($q) use ($keyword) {
                            $q
                                ->orWhere('first_name', 'like', "%" . $keyword . "%")
                                ->orWhere('last_name', 'like', "%" . $keyword . "%");
                        });
                    }
                }

                if (request()->has('search_age_from')) {
                    $from = request('search_age_from');
                    if ($from) {
                        $query->where('age', '>=', intval($from));
                    }
                }
                if (request()->has('search_age_to')) {
                    $to = request('search_age_to');
                    if ($to) {
                        $query->where('age', '<=', intval($to));
                    }
                }

                if (request()->has('search_workplace')) {
                    if (request('search_workplace')) {
                        $query->where('company_id', '=', request('search_workplace'));
                    }
                }
            })
            ->order(function ($query) {
                $query->orderBy('updated_at', 'desc');
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
