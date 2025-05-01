<?php

namespace App\Http\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;

class FormSubmitTrainingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'district' => 'required',
            'county' => 'required',
            'theme' => 'required',
            'observations' => 'required',
        ];
    }
}
