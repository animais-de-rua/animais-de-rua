<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use App\Http\Requests\Request;
use App\Models\Process;
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
            'name_after' => 'nullable|min:2|max:255',
            'process_id' => 'required|exists:processes,id',
            'fat_id' => 'required|exists:fats,id',
            'age.1' => 'numeric|min:0|max:30',
            'age.2' => 'numeric|min:0|max:12',
            'gender' => 'required|in:' . EnumHelper::keys('animal.gender', ','),
            'sterilized' => 'nullable|in:0,1',
            'vaccinated' => 'nullable|in:0,1',
            'processed' => 'nullable|in:0,1',
            'features' => 'nullable|max:4096',
            'history' => 'nullable|max:4096',
            'images' => 'required',
            'adoption_date' => 'required|date',
            'status' => 'in:' . EnumHelper::keys('adoption.status', ','),
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

            // Check if process has treatments
            if ($this->input('processed')) {
                $process = Process::where('id', $this->input('process_id'))->first();

                $count = 0;
                if ($process) {
                    foreach ($process->appointments as $appointment) {
                        $count += count($appointment->treatments);
                    }
                }

                if (!$count) {
                    $validator->errors()->add('processed', __("There are no treatments in the process, so this animal can't be already treated"));
                }
            }

            // Check if status is open with an adopter
            if ($this->input('adopter_id') && !in_array($this->input('status'), ['closed'])) {
                return $validator->errors()->add('status', __('Adoption status is :status, it must be set to closed in order to add an Adopter.', [
                    'status' => __($this->input('status')),
                ]));
            }

            // Check if status is closed or archived with NO adopter
            if (!$this->input('adopter_id') && in_array($this->input('status'), ['closed'])) {
                return $validator->errors()->add('status', __('Adoption cannot be :status without an Adopter.', [
                    'status' => __($this->input('status')),
                ]));
            }
        });
    }
}
