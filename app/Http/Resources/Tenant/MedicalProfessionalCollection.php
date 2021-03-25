<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MedicalProfessionalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {

            switch ($row->medical_speciality_id) {
                case 1:
                    $row->medical_speciality = "ENFERMERO/A";
                    break;
                case 2:
                    $row->medical_speciality = "ANESTECIOLOGO/A";
                    break;
                case 3:
                    $row->medical_speciality = "TERAPEUTA";
                    break;
                case 4:
                    $row->medical_speciality = "PSICOLOGO/A";
                    break;
            }


            return [
                'id' => $row->id,
                'name' => $row->name,
                'document_type' => optional($row->identity_document_type)->description,
                'medical_department' => optional($row->medical_department)->name,
                'document_number' => $row->document_number,
                'mobile_phone' => $row->mobile_phone,
                'availability' => (bool) $row->availability,
                'medical_speciality' => $row->medical_speciality,
            ];
        });
    }
}
