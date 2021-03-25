<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PatientCollection extends ResourceCollection
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

            switch ($row->marital_status){
                case 'S':
                    $row->marital_status = "Soltero/a";
                    break;
                case 'C':
                    $row->marital_status =  "Casado/a";
                    break;
                case 'V':
                    $row->marital_status =  "Viudo/a";
                    break;
                case 'D':
                    $row->marital_status =  "Divorciado/a";
                    break;
            }

            return [
                'id' => $row->id,
                'name' => $row->name,
                'firstname' => $row->firstname,
                'lastname' => $row->lastname,
                'document_type' => optional($row->identity_document_type)->description,
                'document_number' => $row->document_number,
                'mobile_phone' => $row->mobile_phone,
                'age' => $row->age,
                'marital_status' => $row->marital_status,
            ];
        });
    }
}
