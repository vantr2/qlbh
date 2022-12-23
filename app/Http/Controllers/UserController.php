<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\SettingRequest;
use App\Http\Requests\UserRequest;
use App\Models\Customer;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * define route 
     *
     * @return void
     */
    public static function routes()
    {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.list');
            Route::get('/render-data', [UserController::class, 'renderData'])->name('users.render_data');
            Route::get('/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
            Route::post('/change-role', [UserController::class, 'changeRole'])->name('users.change_role');
        });
    }

    /**
     * Create new a instance
     *
     * @param  UserService $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware(['auth', 'admin']);
        $this->userService = $userService;
    }


    /**
     * Go to list page
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return view('users.list');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderData()
    {
        return $this->userService->buildData();
    }

    /**
     * Delete user with id
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $isDeleted = $this->userService->delete($request->id);

        if ($isDeleted) {
            return redirect()->route('users.list')->with('success', __('User has deleted successful'));
        }

        return redirect()->route('users.list')->with('fail', __('An error occurs'));
    }

    /**
     * Save setting for selected user
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function changeRole(Request $request)
    {
        $isChanged = $this->userService->changeRoleSetting($request->all());

        if ($isChanged) {
            return redirect()->route('users.list')->with('success', __('User has change role setting successfully'));
        }

        return redirect()->route('users.list')->with('fail', __('An error occurs'));
    }
}
