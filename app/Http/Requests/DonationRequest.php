<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
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
            'value' => 'required|numeric',
            'process_id' => 'required|exists:processes,id',
            'type' => 'required|in:' . EnumHelper::keys('donation.type', ','),
            'godfather_id' => 'nullable|exists:godfathers,id',
            'headquarter_id' => 'nullable|exists:headquarters,id',
            'protocol_id' => 'nullable|exists:protocols,id',
            'date' => 'required',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            switch ($this->input('type')) {
                case 'private':
                    if (!$this->input('godfather_id')) {
                        $validator->errors()->add('godfather_id', __('The field :field is required.', [
                            'field' => __('godfather'),
                        ]));
                    }
                    $this->request->set('headquarter_id', null);
                    $this->request->set('protocol_id', null);
                    break;

                case 'headquarter':
                    if (!$this->input('headquarter_id')) {
                        $validator->errors()->add('headquarter_id', __('The field :field is required.', [
                            'field' => __('godfather'),
                        ]));
                    }
                    $this->request->set('godfather_id', null);
                    $this->request->set('protocol_id', null);
                    break;

                case 'protocol':
                    if (!$this->input('protocol_id')) {
                        $validator->errors()->add('protocol_id', __('The field :field is required.', [
                            'field' => __('godfather'),
                        ]));
                    }
                    $this->request->set('godfather_id', null);
                    $this->request->set('headquarter_id', null);
                    break;
            }

        });
    }
}
