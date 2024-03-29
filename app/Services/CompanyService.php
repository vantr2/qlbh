<?php

namespace App\Services;

use App\Helpers\Utils;
use App\Models\Company;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class CompanyService
{
    /**
     * store company with data
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
    public function getDetail($id)
    {
        return Company::findOrFail($id);
    }

    /**
     * Delete company by id
     *
     * @param  string $id
     * @return bool true: deleted | false: error
     */
    public function delete($id)
    {
        try {
            Company::where('_id', $id)->delete();
            return true;
        } catch (Exception $ex) {
            Log::error('deleteCompany: ' . $ex);
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
        return DataTables::of(Company::query())
            ->addColumn('action', function ($company) {
                return Utils::renderActionHtml(
                    route('companies.detail', ['id' => $company->id]),
                    route('companies.delete', ['id' => $company->id]),
                    'confirmDeleteCompany(this)'
                );
            })
            ->editColumn('established_year', function ($company) {
                return $company->established_year ?? '';
            })
            ->editColumn('created_by', function ($company) {
                return Utils::actionUser($company->created_by);
            })
            ->editColumn('updated_by', function ($company) {
                return Utils::actionUser($company->updated_by);
            })
            ->rawColumns(['action'])
            ->filter(function ($query) {
                if (request()->has('search_name')) {
                    if (request('search_name')) {
                        $query->where('name', 'like', "%" . request('search_name') . "%");
                    }
                }

                if (request()->has('search_address')) {
                    if (request('search_address')) {
                        $query->where('address', 'like', "%" . request('search_address') . "%");
                    }
                }
            })
            ->order(function ($query) {
                $query->orderBy('updated_at', 'desc');
            })
            ->make(true);
    }
}
