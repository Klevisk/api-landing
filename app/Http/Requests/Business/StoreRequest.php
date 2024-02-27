<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

                'name' => 'required|string',
                'email' => 'required|email|unique:businesses',
                'document' => 'required|string|unique:businesses',
                'slug' => 'required|string|unique:businesses',
                'address' => 'required|string',
                'phone' => 'required|string',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'user_id' => 'required|exists:users,id',
        ];
    }
}
