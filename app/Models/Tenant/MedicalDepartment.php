<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalDepartment extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'medical_departments';

    protected $fillable = [
        'name',
        'description',
        'active',
    ];
}
