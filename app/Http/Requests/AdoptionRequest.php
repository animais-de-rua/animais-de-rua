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
            $process = Process::where('id', $this->input('process_id'))->first();

            if ($this->input('processed')) {
                $count = 0;
                foreach ($process->appointments as $appointment) {
                    $count += count($appointment->treatments);
                }
                if (!$count) {
                    $validator->errors()->add('processed', __("There are no treatments in the process, so this animal can't be already treated"));
                }
            }
        });
    }
}
