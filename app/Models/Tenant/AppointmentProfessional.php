<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentProfessional extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'appointment_professionals';
    protected $with = ['appointment', 'medical_professional'];
    protected $fillable = [
        'appointment_id',
        'medical_professional_id',
        'status',
    ];

    public function appointment()
    {
        return $this->belongsTo(appointment::class, 'appointment_id');
    }

    public function medical_professional()
    {
        return $this->belongsTo(MedicalProfessional::class, 'medical_professional_id');
    }


}
