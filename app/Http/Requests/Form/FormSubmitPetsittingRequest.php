<?php

namespace App\Http\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormSubmitPetsittingRequest extends FormRequest
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
            'first_name' => 'required|max:35',
            'last_name' => 'required|max:35',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'address' => 'required|max:255',
            'city' => 'required|max:35',
            'town' => 'required|max:35',
            'initial_date' => 'required|date',
            'final_date' => 'required|date|after_or_equal:initialDate',
            'animals' => 'required|array|min:1',
            'other_animals' => Rule::requiredIf(function () {
                if ($this->filled('animals')) {
                    return in_array('Outros', $this->input('animals'));
                }
            }),
            'number_of_animals' => 'required|numeric|min:1|max:2',
            'animal_temper' => 'required|max:255',
            'visit_number' => 'required|numeric',
            'walk_number' => 'required_if:has_walk,yes|numeric',
            'services' => 'nullable',
            'notes' => 'nullable|max:255',
            'has_consent' => 'required',
        ];
    }
}
