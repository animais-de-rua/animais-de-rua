<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
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
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|min:2|max:4096',
            'email' => 'nullable|email',
            'phone' => 'nullable|max:1024',
            'url' => 'nullable|url',
            'address' => 'nullable|min:2|max:255',
            'latlong' => 'nullable|min:2|max:255',
            'benefit' => 'nullable|min:2|max:4096',
            'notes' => 'nullable|min:2|max:4096',
            'status' => 'in:0,1',
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
