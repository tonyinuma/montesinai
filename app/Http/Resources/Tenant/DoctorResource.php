<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Module;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'name' => $this->name,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'identity_document_type_id' => $this->identity_document_type_id,
            'medical_department_id' => $this->medical_department_id,
            'document_number' => $this->document_number,
            'mobile_phone' => $this->mobile_phone,

            /*'addresses' => collect($this->addresses)->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'trade_name' => $row->trade_name,
                    'country_id' => $row->country_id,
                    'location_id' => !is_null($row->location_id)?$row->location_id:[],
                    'address' => $row->address,
                    'phone' => $row->phone,
                    'email' => $row->email,
                    'main' => (bool)$row->main,
                ];
            }),*/

        ];
    }
}
