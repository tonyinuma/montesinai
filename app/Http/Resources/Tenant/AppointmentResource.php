<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'allocator_user' => optional($this->allocator_user)->name,
            'patient_id' => $this->patient_id,
            'doctor' => optional($this->quotation->person)->name,
            'admission_date' => $this->admission_date,
            'admission_time' => $this->admission_time,
            'departure_date' => $this->departure_date,
            'departure_time' => $this->departure_time,
            'room_type' => $this->room_type,
            'room_number' => $this->room_number,
            'origin' => $this->origin,
            'observation' => $this->observation,
            'status' => $this->status,
        ];
    }
}
