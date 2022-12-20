<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class Utils
{
    /**
     * Render action html include update, delete button 
     *
     * @param  string $updateRoute
     * @param  string $deleteRoute
     * @param  string $deleteFunction
     * @return string html 
     */
    public static function renderActionHtml($updateRoute, $deleteRoute, $deleteFunction)
    {
        return "
        <div class='d-flex justify-content-center align-items-center'>
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
        $currentUser = Auth::user()->name;
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
}
