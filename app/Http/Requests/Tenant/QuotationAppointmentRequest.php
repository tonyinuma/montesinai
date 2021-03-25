<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class QuotationAppointmentRequest extends FormRequest
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
            'sex' => ['required'],
            'age' => ['required', 'numeric', 'integer'],
            'civil_status' => ['required'],
            'mobile_phone' => ['required'],
            'nationality' => ['required'],
            'origin' => ['required'],
            'room_number' => ['required', 'numeric', 'integer'],
            'date_entry' => ['required'],
            'time_entry' => ['required'],
            'date_exit' => ['required', 'after_or_equal:date_entry'],
            'time_exit' => ['required'],
            'occupation' => ['required'],
            'room_type' => ['required'],
        ];
    }
}
