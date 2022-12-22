<?php

namespace App\Services;

use App\Helpers\Utils;
use App\Models\Product;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ProductService
{
    /**
     * store product with data
     *
     * @param  array $data
     * @return bool true: created | false: error
     */
    public function store($data)
    {
        try {
            if (isset($data['_id'])) {
                $id = $data['_id'];
                unset($data['_id']);
                Product::where('_id', $id)->update($data);
            } else {
                Product::create($data);
            }
            return true;
        } catch (Exception $ex) {
            Log::error('storeProduct: ' . $ex);
        }
        return false;
    }

    /**
     * Get product detail
     *
     * @param  string $id
     * @return Product
     */
    public function getDetail($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Delete product by id
     *
     * @param  string $id
     * @return bool true: deleted | false: error
     */
    public function delete($id)
    {
        try {
            Product::where('_id', $id)->delete();
            Customer::where('product_id', $id)->update(['product_id', null]);
            return true;
        } catch (Exception $ex) {
            Log::error('deleteProduct: ' . $ex);
        }
        return false;
    }

    /**
     * Build data for datatable with server side processing
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function buildData()
    {
        return DataTables::of(Product::query())
            ->addColumn('action', function ($product) {
                return Utils::renderActionHtml(
                    route('products.detail', ['id' => $product->id]),
                    route('products.delete', ['id' => $product->id]),
                    'confirmDeleteProduct(this)'
                );
            })
            ->editColumn('description', function ($product) {
                return $product->description ?? '';
            })
            ->editColumn('created_by', function ($product) {
                return Utils::actionUser($product->created_by);
            })
            ->editColumn('updated_by', function ($product) {
                return Utils::actionUser($product->updated_by);
            })
            ->rawColumns(['action'])
            ->filter(function ($query) {
                if (request()->has('search_name')) {
                    if (request('search_name')) {
                        $query->where('name', 'like', "%" . request('search_name') . "%");
                    }
                }

                if (request()->has('search_price_from')) {
                    $from = request('search_price_from');
                    if ($from) {
                        $query->where('price', '>=', intval($from));
                    }
                }
                if (request()->has('search_price_to')) {
                    $to = request('search_price_to');
                    if ($to) {
                        $query->where('price', '<=', intval($to));
                    }
                }
            })
            ->make(true);
    }
}
