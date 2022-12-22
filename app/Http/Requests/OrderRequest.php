<?php

namespace App\Http\Requests;

use App\Helpers\Utils;
use App\Models\Customer;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_id' => ['required', 'exists:customers,_id'],
            'order_date' => ['required', 'date'],
            'total' => ['required', 'min:0', 'numeric'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->request->remove('_token');
        Utils::attachUserAction($this);

        $orderDate = DateTime::createFromFormat('d/m/Y', $this->order_date);
        $this->merge([
            'details' => $this->constructDetailData(),
            'total' => intval(str_replace(',', '', $this->total)),
            'order_date' => $orderDate->format('Y-m-d'),
        ]);
        $this->request->remove('detail_data');
    }

    /**
     * Construct detail data
     *
     * @return array
     */
    public function constructDetailData()
    {
        $detailData = [];
        $rawData = json_decode($this->detail_data, true);
        foreach ($rawData as $item) {
            $row = [
                'product_id' => $item['id'],
                'product_qty' => $item['quantity'],
                'product_amount' => $item['amount'],
                'product_price' => $item['price'],
            ];
            $detailData[] = $row;
        }
        return $detailData;
    }
}
