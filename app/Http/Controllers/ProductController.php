<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * define route 
     *
     * @return void
     */
    public static function routes()
    {
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('products.list');
            Route::get('/render-data', [ProductController::class, 'renderData'])->name('products.render_data');
            Route::get('/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/store', [ProductController::class, 'store'])->name('products.store');
            Route::get('/detail/{id}', [ProductController::class, 'detail'])->name('products.detail');
            Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('products.delete');
        });
    }

    /**
     * Create new a instance
     *
     * @param  ProductService $productService
     * @return void
     */
    public function __construct(ProductService $productService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
    }


    /**
     * Go to list page
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return view('products.list');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderData()
    {
        return $this->productService->buildData();
    }

    /**
     * Go to create page with form
     *
     * @param  Request $request
     * @return View
     */
    public function create(Request $request)
    {
        return view('products.create');
    }

    /**
     * Store products with form data
     *
     * @param  ProductRequest $request
     * @return RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        $formData = $request->all();
        $isCreated = $this->productService->store($formData);

        if ($isCreated) {
            $successMsg = isset($formData['_id']) ? __('Product has updated successful') : __('Product has created successful');
            return redirect()->route('products.list')->with('success', $successMsg);
        }

        return redirect()->route('products.list')->with('fail', __('An error occurs'));
    }

    /**
     * Go to detail page by id
     *
     * @param  Request $request
     * @return View
     */
    public function detail(Request $request)
    {
        $productInfo = $this->productService->getDetail($request->id);
        return view('products.detail', compact('productInfo'));
    }

    /**
     * Delete company with id
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $isDeleted = $this->productService->delete($request->id);

        if ($isDeleted) {
            return redirect()->route('products.list')->with('success', __('Product has deleted successful'));
        }

        return redirect()->route('products.list')->with('fail', __('An error occurs'));
    }
}
