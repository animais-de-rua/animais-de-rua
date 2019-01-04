<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use App\Http\Requests\Request;
use App\Models\Process;
use Illuminate\Foundation\Http\FormRequest;

class ProcessRequest extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'contact' => 'required|min:3|max:255',
            'phone' => 'required|min:9|max:16',
            'email' => 'required|email',
            'address' => 'required|min:3|max:255',
            'territory_id' => 'required|exists:territories,id',
            'headquarter_id' => 'exists:headquarters,id',
            'amount_males' => 'required|numeric|min:0|max:100',
            'amount_females' => 'required|numeric|min:0|max:100',
            'amount_other' => 'required|numeric|min:0|max:100',
            'specie' => 'required|in:' . EnumHelper::keys('process.specie', ','),
            'status' => 'in:' . EnumHelper::keys('process.status', ','),
            'urgent' => 'nullable|in:0,1',
            'history' => 'required',
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

            // Validate Process balance as positive before close or archive it
            if (in_array($this->input('status'), ['archived', 'closed'])) {
                $id = $this->input('id');

                $process = Process::with(['donations' => function ($query) use ($id) {
                    $query->selectRaw('process_id, sum(value) as total_donations')->where('process_id', $id);
                }])->with(['treatments' => function ($query) use ($id) {
                    $query->selectRaw('process_id, sum(expense) as total_expenses')->where('process_id', $id);
                }])->find($id);

                if ($process->getBalance() < 0) {
                    return $validator->errors()->add('status', __('As this process balance is :balance€, you cannot set the status to :status.', [
                        'status' => __($this->input('status')),
                        'balance' => $process->getBalance(),
                    ]));
                }
            }
        });
    }
}
