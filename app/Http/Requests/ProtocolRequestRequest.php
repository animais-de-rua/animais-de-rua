<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProtocolRequestRequest extends FormRequest
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
            'council' => 'required|min:1',
            'name' => 'required|min:3|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|min:9|max:16',
            'address' => 'required|min:3|max:255',
            'description' => 'nullable|min:3|max:4096',
            'territory_id' => 'required|exists:territories,id',
            'process_id' => 'nullable|exists:processes,id',
            'protocol_id' => 'required|exists:protocols,id',
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
