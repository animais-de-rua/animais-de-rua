<?php

namespace App\Http\Requests\Form;

use App\Enums\Animal\SpeciesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormSubmitApplyRequest extends FormRequest
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
            'process' => 'required',
            'address' => 'required',
            'postalcode' => 'required',
            'animals' => 'required|numeric',
            'specie' => [
                'required',
                Rule::in(SpeciesEnum::class),
            ],
            'parish' => 'required|exists:territories,id',
            'images.*' => 'required|mimes:jpeg,jpg,png|max:5000',
            'observations' => 'required|min:3',
            'colab' => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('images') && count($this->file('images')) < 3) {
                $validator->errors()->add('images', __('You must upload at least 3 images.'));
            }
        });
    }
}
