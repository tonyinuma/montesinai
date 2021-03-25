<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Module;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->id,
            'name' => $this->name,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'identity_document_type_id' => $this->identity_document_type_id,
            'number' => $this->document_number,
            'mobile_phone' => $this->mobile_phone,
            'sex' => $this->sex,
            'age' => $this->age,
            'civil_status' => $this->marital_status,
            'nationality' => $this->nationality,
            'origin' => $this->origin,
            'occupation' => $this->occupation,
        ];
    }
}
