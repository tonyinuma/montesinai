<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'appointments';
    protected $with = ['allocator_user', 'patient', 'doctor', 'medical_service', 'room_types'];

    protected $fillable = [
        'allocator_user_id',
        'quotation_id',
        'patient_id',
        'doctor_id',
        'medical_department_id',
        'room_type',
        'room_number',
        'origin',
        'admission_date',
        'admission_time',
        'departure_date',
        'departure_time',
        'observation',
        'price',
        'status',
    ];

    public function allocator_user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medical_service()
    {
        return $this->belongsTo(MedicalService::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function room_types()
    {
        return $this->belongsTo(RoomType::class, 'room_type');
    }
}
