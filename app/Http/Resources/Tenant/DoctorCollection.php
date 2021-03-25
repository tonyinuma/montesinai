<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return [
                'id' => $row->id,
                'name' => $row->name,
                'firstname' => $row->firstname,
                'lastname' => $row->lastname,
                'document_type' => optional($row->identity_document_type)->description,
                'medical_department' => optional($row->medical_department)->name,
                'document_number' => $row->document_number,
                'mobile_phone' => $row->mobile_phone,
            ];
        });
    }
}
