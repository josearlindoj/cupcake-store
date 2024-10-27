<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
