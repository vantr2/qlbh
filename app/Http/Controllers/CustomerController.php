<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Helpers\Utils;
use App\Http\Requests\CustomerRequest;
use App\Imports\CustomerImport;
use App\Models\Company;
use App\Models\Customer;
use App\Services\CustomerService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
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
            Route::get('/export', [CustomerController::class, 'export'])->name('customers.export');
            Route::get('/download-sample', [CustomerController::class, 'downloadSample'])->name('customers.download_sample');
            Route::get('/download-error-file/{file_name}', [CustomerController::class, 'downloadErrorFile'])->name('customers.download_error_file');
            Route::post('/import', [CustomerController::class, 'import'])->name('customers.import');
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
        return $this->customerService->buildData();
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

    /**
     * export customer list to excel
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $fileNameFormat = config('excel.exports.result_file_name_format.customer');
        $fileName = sprintf($fileNameFormat, date('YmdHis'));
        return (new CustomerExport)->download($fileName);
    }

    /**
     * import customer list from excel
     *
     * @param  Request $request
     * @return void
     */
    public function import(Request $request)
    {
        $errorFileName = '';
        try {
            $filePath = storage_path('app/') . $request->file('file')->store('local');

            if (File::exists($filePath)) {
                Excel::import(new CustomerImport, $filePath);
                File::delete($filePath);
            }
            return redirect()->route('customers.list')->with('success', __('Customer has imported successfully'));
        } catch (ValidationException $e) {
            $errorFileName = $this->customerService->exportErrorFile($e->failures());
        } catch (Exception $ex) {
            Log::error('importCustomer: ' . $ex);
        }
        return redirect()->route('customers.list')
            ->with('import_error', __(
                'Unable import customer. For details please download <a href=":link">this file</a>',
                [
                    'link' => route('customers.download_error_file', ['file_name' => $errorFileName])
                ]
            ));
    }

    /**
     * Download sample data in order to import customer from excel
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSample(Request $request)
    {
        $filePath = config('excel.imports.sample_path.customers');
        return response()->download($filePath);
    }

    /**
     * Download sample data in order to import customer from excel
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadErrorFile(Request $request)
    {
        $errorPath = config('excel.imports.error.path');
        $filePath = storage_path('app/' . $errorPath) . '/' . $request->file_name;

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
