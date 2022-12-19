<?php

namespace App\Helpers;

class Utils
{
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
}
