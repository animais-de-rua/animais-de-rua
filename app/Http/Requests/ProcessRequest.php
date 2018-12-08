<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ProcessRequest extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'contact' => 'required|min:3|max:255',
            'phone' => 'required|min:9|max:14',
            'email' => 'required|email',
            'latlong' => 'required|min:3|max:255',
            'territory_id' => 'required|exists:territories,id',
            'headquarter_id' => 'required|exists:headquarters,id',
            'amount_males' => 'required|numeric|min:0|max:100',
            'amount_females' => 'required|numeric|min:0|max:100',
            'amount_other' => 'required|numeric|min:0|max:100',
            'specie' => 'required|in:' . EnumHelper::keys('process.specie', ','),
            'status' => 'required|in:' . EnumHelper::keys('process.status', ','),
            'urgent' => 'required|in:0,1',
            'history' => 'required',
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
