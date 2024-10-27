<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    // Allow all users to make this request
    public function authorize()
    {
        return true;
    }

    // Define validation rules
    public function rules()
    {
        return [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ];
    }
}
