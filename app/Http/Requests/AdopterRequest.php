<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdopterRequest extends FormRequest
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
            'name' => 'required|min:5|max:255',
            'email' => 'nullable|required_without:phone|email',
            'phone' => 'nullable|required_without:email|min:9|max:16',
            'address' => 'required|min:2|max:255',
            'zip_code' => 'required|min:2|max:255',
            'id_card' => 'required|min:6|max:14',
            'territory_id' => 'required|exists:territories,id',
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
