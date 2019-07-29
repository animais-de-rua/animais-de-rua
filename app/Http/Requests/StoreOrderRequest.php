<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\StoreOrder;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'reference' => 'required_without:id',
            'recipient' => 'required_without:id',
            'address' => 'required_without:id',
            'user_id' => 'required_without:id|exists:users,id',
            'shipment_date' => 'nullable|date',
            'expense' => 'nullable|numeric|min:0|max:1000000',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $id = $this->input('id');

            // Common user cannot edit a shipped order
            if (!is('admin', 'store orders') && StoreOrder::find($id)->shipment_date) {
                return $validator->errors()->add('sent', __("You don't have the permissions to update an order already shipped."));
            }
        });
    }
}
