<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class MedicalProfessionalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'document_number' => [
                'required'
            ],
            'name' => [
                'required'
            ],
            'mobile_phone' => [
                'required',
                'min:5',
            ],
            'medical_speciality_id' => [
                'required',
            ]
        ];
    }
}
