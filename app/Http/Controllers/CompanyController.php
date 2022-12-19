<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    /**
     * @var CompanyService
     */
    protected $companySerivce;

    /**
     * define route 
     *
     * @return void
     */
    public static function routes()
    {
        Route::prefix('companies')->group(function () {
            Route::get('/', [CompanyController::class, 'index'])->name('companies.list');
            Route::get('/render-data', [CompanyController::class, 'renderData'])->name('companies.render_data');
            Route::get('/create', [CompanyController::class, 'create'])->name('companies.create');
            Route::post('/store', [CompanyController::class, 'store'])->name('companies.store');
            Route::get('/detail/{id}', [CompanyController::class, 'detail'])->name('companies.detail');
            Route::get('/delete/{id}', [CompanyController::class, 'delete'])->name('companies.delete');
        });
    }

    /**
     * Create new a instance
     *
     * @param  CompanyService $companySerivce
     * @return void
     */
    public function __construct(CompanyService $companySerivce)
    {
        $this->middleware('auth');
        $this->companySerivce = $companySerivce;
    }


    /**
     * Go to list page
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return view('companies.list');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderData()
    {
        return $this->companySerivce->buildData();
    }

    /**
     * Go to create page with form
     *
     * @param  Request $request
     * @return View
     */
    public function create(Request $request)
    {
        return view('companies.create');
    }

    /**
     * Store companies with form data
     *
     * @param  CompanyRequest $request
     * @return RedirectResponse
     */
    public function store(CompanyRequest $request)
    {
        $formData = $request->all();
        $isCreated = $this->companySerivce->store($formData);

        if ($isCreated) {
            $successMsg = isset($formData['_id']) ? __('Company has updated successful') : __('Company has created successful');
            return redirect()->route('companies.list')->with('success', $successMsg);
        }

        return redirect()->route('companies.list')->with('fail', __('An error occurs'));
    }

    /**
     * Go to detail page by id
     *
     * @param  Request $request
     * @return View
     */
    public function detail(Request $request)
    {
        $companyInfo = $this->companySerivce->getDetail($request->id);
        return view('companies.detail', compact('companyInfo'));
    }

    /**
     * Delete company with id
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $isDeleted = $this->companySerivce->delete($request->id);

        if ($isDeleted) {
            return redirect()->route('companies.list')->with('success', __('Company has deleted successful'));
        }

        return redirect()->route('companies.list')->with('fail', __('An error occurs'));
    }
}
