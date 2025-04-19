<?php

namespace App\Http\Requests;

use Backpack\PermissionManager\app\Http\Requests\UserStoreCrudRequest;

class UserStoreRequest extends UserStoreCrudRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required|confirmed',
        ];

        if (! is('admin')) {
            $rules = array_merge($rules, [
                'friend_card_modality_id' => 'required',
            ]);
        }

        return $rules;
    }
}
