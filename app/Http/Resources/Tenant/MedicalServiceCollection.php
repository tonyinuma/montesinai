<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MedicalServiceCollection extends ResourceCollection
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
                'description' => $row->description,
                'suggested_price' => $row->suggested_price,
                'status' => (bool) $row->status,
//                'doctor_department' => optional($row->doctor_department)->name,
            ];
        });
    }
}
