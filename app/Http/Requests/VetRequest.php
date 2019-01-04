<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class VetRequest extends FormRequest
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
            'email' => 'nullable|required_without:phone|email',
            'phone' => 'nullable|required_without:email|min:9|max:16',
            'url' => 'nullable|url',
            'address' => 'required|min:3|max:1024',
            'status' => 'in:' . EnumHelper::keys('vet.status', ','),
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
