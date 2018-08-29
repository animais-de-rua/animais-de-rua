<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\EnumHelper;

class AnimalRequest extends FormRequest
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
            'adoption_id' => 'required|exists:adoptions,id',
            'name' => 'required|min:2|max:255',
            'age' => 'nullable|numeric|min:0|max:300',
            'gender' => 'nullable|in:'.EnumHelper::keys('animal.gender', ','),
            'sterilized' => 'nullable|in:0,1',
            'vaccinated' => 'nullable|in:0,1'
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
