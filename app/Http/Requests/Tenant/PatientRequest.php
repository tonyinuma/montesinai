<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'number' => [
                'required'
            ],
            'name' => [
                'required'
            ],
            'mobile_phone' => [
                'required',
                'min:5',
            ]
        ];
    }
}
