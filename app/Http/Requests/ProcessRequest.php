<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
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
            'amount_males' => 'required|numeric|min:0|max:200',
            'amount_females' => 'required|numeric|min:0|max:200',
            'amount_other' => 'required|numeric|min:0|max:200',
            'specie' => 'required|in:'.EnumHelper::keys('process.specie', ','),
            'status' => 'in:'.EnumHelper::keys('process.status', ','),
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

            // Validations before close or archive
            if (in_array($this->input('status'), ['archived', 'closed'])) {
                $id = $this->input('id');

                // Validate process balance as positive
                $process = Process::with(['donations' => function ($query) use ($id) {
                    $query->selectRaw('process_id, sum(value) as total_donations')->where('process_id', $id);
                }])->with(['treatments' => function ($query) use ($id) {
                    $query->selectRaw('process_id, sum(expense) as total_expenses')->where('process_id', $id);
                }])->find($id);

                if ($process && $process->getBalance() < 0) {
                    $validator->errors()->add('status', __('As this process balance is :balance€, you cannot set the status to :status.', [
                        'status' => __($this->input('status')),
                        'balance' => $process->getBalance(),
                    ]));
                }

                // Validate no open treatments
                if (Process::find($id)->treatments->where('status', 'approving')->count() > 0) {
                    $validator->errors()->add('status', __('There are some unapproved treatments, you must approve those treatments first.'));
                }

            }

            // Validate one animal at least
            $total_animals = $this->input('amount_males') + $this->input('amount_females') + $this->input('amount_other');
            if ($total_animals <= 0) {
                $validator->errors()->add('animal_count', __('There must be at least one animal in the process, either male, female or undefined.'));
            }

            // Check for 3 minimum valid images
            $images = [];
            if (is_array($this->input('images'))) {
                $images = array_filter($this->input('images'), function ($image) {
                    preg_match('/\.(jpe?g|png)$/i', $image, $matches, PREG_OFFSET_CAPTURE);

                    return $image && $matches;
                });
            }

            if (count($images) < 3) {
                $validator->errors()->add('images', __('You must upload at least 3 images.'));
            }

            // Validate there are no less animals than treatments
            $process = Process::find($this->input('id'));
            if ($process) {
                $process_affected_animals = $process->getTotalAffectedAnimalsNew();

                if ($total_animals < $process_affected_animals) {
                    $validator->errors()->add('animal_count', __("There are :total animals treated on this process, you can't update process animal count to a lower value than the treated animals.", ['total' => $process_affected_animals]));
                }
            }

            // It can only be urgent if status is waiting_godfather
            if ($this->input('urgent') && $this->input('status') != 'waiting_godfather') {
                $validator->errors()->add('urgent', __("Only processes 'waiting for godfather' can be urgent."));
            }

        });
    }
}
