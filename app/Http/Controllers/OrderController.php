<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Helpers\Utils;
use App\Http\Requests\ImportExcelRequest;
use App\Http\Requests\OrderRequest;
use App\Imports\OrderImport;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
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

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    protected $orderService;

    /**
     * define route 
     *
     * @return void
     */
    public static function routes()
    {
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('orders.list');
            Route::get('/render-data', [OrderController::class, 'renderData'])->name('orders.render_data');
            Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
            Route::post('/store', [OrderController::class, 'store'])->name('orders.store');
            Route::get('/detail/{id}', [OrderController::class, 'detail'])->name('orders.detail');
            Route::get('/delete/{id}', [OrderController::class, 'delete'])->name('orders.delete');
            Route::get('/export', [OrderController::class, 'export'])->name('orders.export');
            Route::get('/download-sample', [OrderController::class, 'downloadSample'])->name('orders.download_sample');
            Route::get('/download-error-file/{file_name}', [OrderController::class, 'downloadErrorFile'])->name('orders.download_error_file');
            Route::post('/import', [OrderController::class, 'import'])->name('orders.import');
        });
    }

    /**
     * Create new a instance
     *
     * @param  OrderService $orderService
     * @return void
     */
    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth');
        $this->orderService = $orderService;
    }


    /**
     * Go to list page
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return view('orders.list');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderData()
    {
        return $this->orderService->buildData();
    }

    /**
     * Go to create page with form
     *
     * @param  Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }

    /**
     * Store order with form data
     *
     * @param  OrderRequest $request
     * @return RedirectResponse
     */
    public function store(OrderRequest $request)
    {
        $formData = $request->all();
        $detailData = $formData['details'];
        unset($formData['details']);


        $isCreated = $this->orderService->store($formData, $detailData);

        if ($isCreated) {
            $successMsg = isset($formData['_id']) ? __('Order has updated successful') : __('Order has created successful');
            return redirect()->route('orders.list')->with('success', $successMsg);
        }

        return redirect()->route('orders.list')->with('fail', __('An error occurs'));
    }

    /**
     * Go to detail page by id
     *
     * @param  Request $request
     * @return View
     */
    public function detail(Request $request)
    {
        $customers = Customer::all();
        $products = Product::all();
        $orderInfo = $this->orderService->getDetail($request->id);
        return view('orders.detail', compact('orderInfo', 'customers', 'products'));
    }

    /**
     * Delete order with id
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $isDeleted = $this->orderService->delete($request->id);

        if ($isDeleted) {
            return redirect()->route('orders.list')->with('success', __('Order has deleted successful'));
        }

        return redirect()->route('orders.list')->with('fail', __('An error occurs'));
    }

    /**
     * export order list to excel
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $fileNameFormat = config('excel.exports.result_file_name_format.order');
        $fileName = sprintf($fileNameFormat, date('YmdHis'));
        return (new OrderExport)->download($fileName);
    }

    /**
     * import order list from excel
     *
     * @param  ImportExcelRequest $request
     * @return void
     */
    public function import(ImportExcelRequest $request)
    {
        $errorFileName = '';
        try {
            $filePath = storage_path('app/') . $request->file('file')->store('local');

            if (File::exists($filePath)) {
                Excel::import(new OrderImport, $filePath);
                File::delete($filePath);
            }
            return redirect()->route('orders.list')->with('success', __('Order has imported successfully'));
        } catch (ValidationException $e) {
            $errorFileName = $this->orderService->exportErrorFile($e->failures());
        } catch (Exception $ex) {
            Log::error('importOrder: ' . $ex);
        }
        return redirect()->route('orders.list')
            ->with('import_error', __(
                'Unable import order. For details please download <a href=":link">this file</a>',
                [
                    'link' => route('orders.download_error_file', ['file_name' => $errorFileName])
                ]
            ));
    }

    /**
     * Download sample data in order to import order from excel
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSample(Request $request)
    {
        $filePath = config('excel.imports.sample_path.orders');
        if (File::exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->route('orders.list')->with('fail', __('Sample file not found'));
    }

    /**
     * Download sample data in order to import order from excel
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadErrorFile(Request $request)
    {
        $errorPath = config('excel.imports.error.path');
        $filePath = storage_path('app/' . $errorPath) . '/' . $request->file_name;

        if (File::exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend(true);
        }
        return redirect()->route('orders.list')->with('fail', __('Error file not found'));
    }
}
