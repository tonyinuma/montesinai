<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Module;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalProfessionalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'identity_document_type_id' => $this->identity_document_type_id,
            'medical_department_id' => $this->medical_department_id,
            'document_number' => $this->document_number,
            'mobile_phone' => $this->mobile_phone,
            'availability' => (bool)$this->availability,
            'medical_speciality_id' => $this->medical_speciality_id,
        ];
    }
}
