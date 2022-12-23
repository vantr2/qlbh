<?php

namespace App\Services;

use App\Helpers\Utils;
use App\Models\User;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class UserService
{
    /**
     * Delete user by id
     *
     * @param  string $id
     * @return bool true: deleted | false: error
     */
    public function delete($id)
    {
        try {
            User::where('_id', $id)->delete();
            return true;
        } catch (Exception $ex) {
            Log::error('deleteUser: ' . $ex);
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
        return DataTables::of(User::query())
            ->addColumn('action', function ($user) {
                return $user->isSuperAdmin() ? '' : $this->renderActionHtml(
                    json_encode($user->toArray()),
                    route('users.change_role', ['id' => $user->id]),
                    route('users.delete', ['id' => $user->id]),
                    'confirmDeleteUser(this)'
                );
            })
            ->editColumn('role', function ($user) {
                return $user->roleToText();
            })
            ->editColumn('created_at', function ($user) {
                return date('d-m-Y H:i:s', strtotime($user->created_at));
            })
            ->editColumn('updated_at', function ($user) {
                return date('d-m-Y H:i:s', strtotime($user->updated_at));
            })
            ->editColumn('created_by', function ($user) {
                return Utils::actionUser($user->created_by);
            })
            ->editColumn('updated_by', function ($user) {
                return Utils::actionUser($user->updated_by);
            })
            ->filter(function ($query) {
                if (request()->has('search_name')) {
                    if (request('search_name')) {
                        $query->where('name', 'like', '%' . request('search_name') . '%');
                    }
                }
                if (request()->has('search_email')) {
                    if (request('search_email')) {
                        $query->where('email', 'like', '%' . request('search_email') . '%');
                    }
                }
                if (request()->has('search_role')) {
                    if (request('search_role')) {
                        $query->where('role', '=', intval(request('search_role')));
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
     * Customer list with setting
     *
     * @return Customer[]
     */
    public function customerListWithSetting()
    {
        return Customer::with('beApplied')->get();
    }

    /**
     * Change role setting
     *
     * @param  array $data
     * @return bool true: changed | false: fail
     */
    public function changeRoleSetting($data)
    {
        try {
            User::where('_id', $data['_id'])->update([
                'role' => $data['role'],
            ]);
            return true;
        } catch (Exception $ex) {
            Log::error('changeRoleSetting: ' . $ex);
        }
        return false;
    }

    /**
     * Render action html include setting, delete button 
     *
     * @param  string $userData
     * @param  string $changeRoleRoute
     * @param  string $deleteRoute
     * @param  string $deleteFunction
     * @return string html 
     */
    private function renderActionHtml($userData, $changeRoleRoute, $deleteRoute, $deleteFunction)
    {
        return "
        <div class='d-flex justify-content-center align-items-center'>
            <button data-href='$changeRoleRoute' data-user='$userData'
                class='btn btn-secondary me-2' onclick='showChangeRolePopup(this)'>" . __('Change Role') . "</button>
            <button data-href='$deleteRoute'
                onclick='$deleteFunction'
                class='btn btn-danger delete-action'>" . __('Delete') . "</button>
        </div>
        ";
    }
}
