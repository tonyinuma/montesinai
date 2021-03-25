<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\IdentityDocumentType;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'doctors';
    protected $with = ['identity_document_type', 'medical_department'];
    protected $fillable = [
        'user_id',
        'medical_department_id',
        'doctor_specialty',
        'blood_type_id',
        'gender_id',
        'status_id',
        'identity_document_type_id',
        'name',
        'firstname',
        'lastname',
        'document_number',
        'birthday',
        'mobile_phone',
        'phone'
    ];

    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    public function medical_department()
    {
        return $this->belongsTo(MedicalDepartment::class, 'medical_department_id');
    }
}
