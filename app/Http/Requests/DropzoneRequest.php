<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DropzoneRequest extends FormRequest
{
    public function authorize()
    {
        return \Auth::check();
    }

    public function rules()
    {
        return [
            'file' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'No file specified.'
        ];
    }
}
