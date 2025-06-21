<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reference' => 'required|max:255',
            'voucher' => 'nullable|max:255',
            'value' => 'required_without:percent|numeric|min:0',
            'percent' => 'required_without:value|numeric|min:0|max:100',
            'client_name' => 'nullable|max:255',
            'client_email' => 'nullable|max:255',
            'expiration' => 'nullable',
            'status' => 'in:'.EnumHelper::keys('store.voucher', ','),
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
