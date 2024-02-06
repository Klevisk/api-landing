<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'string',
            'email' => 'email',
            'document' => 'string',
            'address' => 'string',
            'phone' => 'string',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'exists:users,id',
        ];
    }
}
