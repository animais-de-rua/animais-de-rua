<?php

namespace App\Http\Requests;

use App\Models\Adoption;

class AdopterStoreRequest extends AdopterRequest
{
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $adoption = Adoption::where('id', $this->input('adoption_id'))->first();

            // Check if status is open
            if ($adoption->status != 'open') {
                return $validator->errors()->add('adoption_id', __('Adoption status is :status, it must be open in order to add an Adopter.', [
                    'status' => __($adoption->status),
                ]));
            }

            // Close status
            $adoption->status = 'closed';
            $adoption->save();
        });
    }
}
