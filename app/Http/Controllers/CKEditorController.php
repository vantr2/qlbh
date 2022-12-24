<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $imagePath = 'ckeditor/images';

            // generate file name
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            // upload file
            $request->file('upload')->move(public_path($imagePath), $fileName);

            // handle response
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset($imagePath . '/' . $fileName);
            $msg = __('Image successfully uploaded');
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
