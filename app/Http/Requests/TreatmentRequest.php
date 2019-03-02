<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class TreatmentRequest extends FormRequest
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
            'appointment_id' => 'required|exists:appointments,id',
            'treatment_type_id' => 'required|exists:treatment_types,id',
            'vet_id' => 'required|exists:vets,id',
            'affected_animals' => 'required|numeric|min:1|max:100',
            'affected_animals_new' => 'required|numeric|min:0|max:100',
            'expense' => 'required|numeric|min:0|max:1000000',
            'date' => 'required|date|before_or_equal:today',
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
            'date.before_or_equal' => __('The date must be a date before or equal to today.'),
        ];
    }
}
