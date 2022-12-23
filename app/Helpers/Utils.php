<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Utils
{
    /**
     * Render action html include update, delete button 
     *
     * @param  string $updateRoute
     * @param  string $deleteRoute
     * @param  string $deleteFunction
     * @param  string $viewButton
     * @return string html 
     */
    public static function renderActionHtml($updateRoute, $deleteRoute, $deleteFunction, $viewRoute = '')
    {
        $viewButton = '';
        if ($viewRoute) {
            $viewButton = "
                <a href='$viewRoute'
                    class='btn btn-primary me-2'>" . __('View') . "</a>
            ";
        }
        return "
        <div class='d-flex justify-content-center align-items-center'>
            $viewButton
            <a href='$updateRoute'
                class='btn btn-warning me-2'>" . __('Update') . "</a>
            <button data-href='$deleteRoute'
                onclick='$deleteFunction'
                class='btn btn-danger delete-action'>" . __('Delete') . "</button>
        </div>
        ";
    }

    /**
     * Attach user action, include created_by, updated_by
     *
     * @param  Request $request
     * @return void
     */
    public static function attachUserAction(&$request)
    {
        $currentUser = Auth::user()->id;
        if ($request->request->has('_id')) {
            $request->request->add([
                'updated_by' => $currentUser,
            ]);
        } else {
            $request->request->add([
                'created_by' => $currentUser,
                'updated_by' => $currentUser,
            ]);
        }
    }

    /**
     * Format date
     * 
     * @param string $date
     * @return string
     */
    public static function formatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    /**
     * Display user name of created_by, updated_by
     *
     * @param  string $by
     * @return string
     */
    public static function actionUser($by)
    {
        if (!$by) {
            return $by;
        }

        $user = User::find($by);
        if ($user) {
            return $user->name;
        }
        return $by;
    }
}
