<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\CustomerRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * @var CustomerService
     */
    protected $customerService;

    /**
     * define route 
     *
     * @return void
     */
    public static function routes()
    {
        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('customers.list');
            Route::get('/render-data', [CustomerController::class, 'renderData'])->name('customers.render_data');
            Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
            Route::post('/store', [CustomerController::class, 'store'])->name('customers.store');
            Route::get('/detail/{id}', [CustomerController::class, 'detail'])->name('customers.detail');
            Route::get('/delete/{id}', [CustomerController::class, 'delete'])->name('customers.delete');
        });
    }

    /**
     * Create new a instance
     *
     * @param  CustomerService $customerService
     * @return void
     */
    public function __construct(CustomerService $customerService)
    {
        $this->middleware('auth');
        $this->customerService = $customerService;
    }


    /**
     * Go to list page
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return view('customers.list');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderData()
    {
        return DataTables::of(Customer::query()->with('company'))
            ->addColumn('name', function ($customer) {
                return $customer->first_name . $customer->last_name;
            })
            ->addColumn('workplace', function ($customer) {
                return $customer->company->name;
            })
            ->addColumn('action', function ($customer) {
                return Utils::renderActionHtml(
                    route('customers.detail', ['id' => $customer->id]),
                    route('customers.delete', ['id' => $customer->id]),
                    'confirmDeleteCustomer(this)'
                );
            })
            ->editColumn('gender', function ($customer) {
                $genderToText = [
                    Customer::MALE => __('Male'),
                    Customer::FEMALE => __('Female'),
                    Customer::OTHER => __('Other')
                ];
                return $genderToText[intval($customer->gender)];
            })
            ->editColumn('type', function ($customer) {
                $typeToText = [Customer::VIP => __('VIP'), Customer::NORMAL => __('Normal')];
                return $typeToText[intval($customer->type)];
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Go to create page with form
     *
     * @param  Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $companies = Company::all();
        return view('customers.create', compact('companies'));
    }

    /**
     * Store customer with form data
     *
     * @param  CustomerRequest $request
     * @return RedirectResponse
     */
    public function store(CustomerRequest $request)
    {
        $formData = $request->all();
        $isCreated = $this->customerService->store($formData);

        if ($isCreated) {
            $successMsg = isset($formData['_id']) ? __('Customer has updated successful') : __('Customer has created successful');
            return redirect()->route('customers.list')->with('success', $successMsg);
        }

        return redirect()->route('customers.list')->with('fail', __('An error occurs'));
    }

    /**
     * Go to detail page by id
     *
     * @param  Request $request
     * @return View
     */
    public function detail(Request $request)
    {
        $companies = Company::all();
        $customerInfo = $this->customerService->getDetail($request->id);
        return view('customers.detail', compact('customerInfo', 'companies'));
    }

    /**
     * Delete customer with id
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $isDeleted = $this->customerService->delete($request->id);

        if ($isDeleted) {
            return redirect()->route('customers.list')->with('success', __('Customer has deleted successful'));
        }

        return redirect()->route('customers.list')->with('fail', __('An error occurs'));
    }
}
