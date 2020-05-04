<?php

namespace App\Http\Requests;

use Backpack\PermissionManager\app\Http\Requests\UserUpdateCrudRequest;

class UserUpdateRequest extends UserUpdateCrudRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => 'nullable|unique:users,email,' . $this->get('id'),
            'name' => 'nullable',
            'password' => 'nullable|confirmed',
        ];

        if (!is('admin')) {
            $rules = array_merge($rules, [
                'friend_card_modality_id' => 'required',
            ]);
        }

        return $rules;
    }
}
