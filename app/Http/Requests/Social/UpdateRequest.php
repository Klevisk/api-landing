<?php

namespace App\Http\Requests\Social;

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
            'name' => 'sometimes|required|string',
            'urls' => 'sometimes|required|string',
            'business_id' => 'exists:businesses,id',
        ];
    }
}
