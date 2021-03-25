<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class PatientAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'number' => ['required', 'numeric'], // document_number
            'name' => ['required'],
            'identity_document_type_id' => ['required'],
            'mobile_phone' => ['required'],
            'nationality' => ['required'],
            'date_entry' => ['required'],
            'time_entry' => ['required'],
            'occupation' => ['required'],
            'date_exit' => ['required', 'after_or_equal:date_entry'],
            'time_exit' => ['required'],
            'room_type' => ['required'],
            'origin' => ['required'],
        ];
    }
}
