<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            'process_id' => 'required|exists:processes,id',
            'vet_id_1' => 'required|exists:vets,id',
            'date_1' => 'required|date',
            'vet_id_2' => 'nullable|exists:vets,id',
            'date_2' => 'nullable|date',
            'amount_males' => 'required|numeric|min:0|max:100',
            'amount_females' => 'required|numeric|min:0|max:100',
            'status' => 'in:' . EnumHelper::keys('appointment.status', ','),
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
