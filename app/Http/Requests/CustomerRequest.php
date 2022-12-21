<?php

namespace App\Http\Requests;

use App\Helpers\Utils;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'age' => ['numeric', 'min:0'],
            'gender' => [
                'required',
                Rule::in([Customer::MALE, Customer::FEMALE, Customer::OTHER])
            ],
            'birthday' => ['date', 'before:today'],
            'type' => [
                Rule::in([Customer::VIP, Customer::NORMAL])
            ],
            'company_id' => ['required', 'exists:companies,_id'],
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
    }
}
