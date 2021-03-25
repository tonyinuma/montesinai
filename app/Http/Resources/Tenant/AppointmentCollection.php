<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppointmentCollection extends ResourceCollection
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

            switch ($row->status){
                case '0':
                    $row->status = "Cotizado";
                    break;
                case '1':
                    $row->status =  "Aprobado";
                    break;
                case '2':
                    $row->status =  "En curso";
                    break;
                case '3':
                    $row->status =  "Finalizado";
                    break;
                case '4':
                    $row->status =  "Descartado";
                    break;
            }

            return [
                'id' => $row->id,
                'allocator_user' => optional($row->allocator_user)->name,
                'patient' => optional($row->patient)->name,
                'cliente' => optional($row->quotation->person)->name,
                'admission_date' => $row->admission_date,
                'departure_date' => $row->departure_date,
                'room_number' => $row->room_number,
                'room_type' => $row->room_types->description,
                'price' => optional($row->quotation)->total,
                'status' => $row->status,
            ];
        });
    }
}
