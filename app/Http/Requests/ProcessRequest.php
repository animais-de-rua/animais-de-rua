<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\EnumHelper;

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
        return \Auth::check();
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
            'contact' => 'nullable|min:3|max:255',
            'phone' => 'nullable|min:9|max:14',
            'email' => 'nullable|email',
            'latlong' => 'nullable|min:3|max:255',
            'territory_id' => 'nullable|exists:territories,id',
            'headquarter_id' => 'nullable|exists:headquarters,id',
            'amount_males' => 'required|numeric|min:0|max:100',
            'amount_females' => 'required|numeric|min:0|max:100',
            'amount_other' => 'required|numeric|min:0|max:100',
            'specie' => 'in:'.EnumHelper::keys('process.specie'),
            'status' => 'in:'.EnumHelper::keys('process.status'),
            'donation_id' => 'exists:donations,id',
            'treatment_id' => 'exists:treatments,id'
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
