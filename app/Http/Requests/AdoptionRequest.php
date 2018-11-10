<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class AdoptionRequest extends FormRequest
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
            'process_id' => 'required|exists:processes,id',
            'fat_id' => 'required|exists:users,id',
            'age.1' => 'numeric|min:0|max:30',
            'age.2' => 'numeric|min:0|max:12',
            'specie' => 'required|in:' . EnumHelper::keys('process.specie', ','),
            'gender' => 'required|in:' . EnumHelper::keys('animal.gender', ','),
            'sterilized' => 'nullable|in:0,1',
            'vaccinated' => 'nullable|in:0,1',
            'history' => 'nullable|max:4096',
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
