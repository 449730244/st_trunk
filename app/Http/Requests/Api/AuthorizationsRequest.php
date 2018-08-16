<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class AuthorizationsRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string|min:6'
        ];
    }
}
