<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalService extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'medical_services';
    protected $with = ['medical_department'];
    protected $fillable = [
        'medical_department_id',
        'name',
        'description',
        'suggested_price',
        'status',
    ];

    public function medical_department()
    {
        return $this->belongsTo(MedicalDepartment::class, 'medical_department_id');
    }
}
