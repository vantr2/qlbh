<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $me = Auth::user();
        return view('profile', compact('me'));
    }

    public function save(ProfileRequest $request)
    {
        $fileName = '';
        try {
            $me = Auth::user();
            $oldAvatar = $me->avatar;

            // store image
            $fileName = sprintf('user__%d__%s.jpg', $me->id, uniqid());
            $request->file('file')->storeAs('', $fileName, ['disk' => 'avatars']);

            //update profile
            $data = $request->except('file');
            $data['avatar'] = $fileName;
            User::where('_id', $me->id)->update($data);

            //delete old image
            if ($oldAvatar !== 'no-user.png' && File::exists(public_path('images/' . $oldAvatar))) {
                File::delete(public_path('images/' . $oldAvatar));
            }

            return redirect()->back()->with('success', __('My profile has updated successful'));
        } catch (Exception $ex) {
            Log::error('saveProfile: ' . $ex);
            //delete image when error
            if ($fileName && File::exists(public_path('images/' . $fileName))) {
                File::delete(public_path('images/' . $fileName));
            }
        }

        return redirect()->back()->with('fail', __('An error occurs'));
    }
}
