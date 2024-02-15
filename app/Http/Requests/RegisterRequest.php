<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'

        ];
    }
}
