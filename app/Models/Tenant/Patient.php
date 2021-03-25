<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\IdentityDocumentType;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'patients';
    protected $with = ['identity_document_type'];
    protected $fillable = [
        'user_id',
        'identity_document_type_id',
        'document_number',
        'name',
        'firstname',
        'lastname',
        'email',
        'birthday',
        'mobile_phone',
        'occupation',
        'age',
        'marital_status',
        'nationality',
        'phone',
        'gender_id',
        'sex',
        'blood_type_id',
        'status_id',
    ];

    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    public static function quoteAppointment($quotation_id, $array_patient, $array_professional)
    {
        $patient = Patient::firstOrNew(['id' => $array_patient['patient_id']]);
        $patient->identity_document_type_id = $array_patient['identity_document_type_id'];
        $patient->document_number = $array_patient['number'];
        $patient->name = $array_patient['name'];
        $patient->occupation = $array_patient['occupation'];
        $patient->nationality = $array_patient['nationality'];
        $patient->mobile_phone = $array_patient['mobile_phone'];
        $patient->save();

        $appointment = Appointment::create([
                "allocator_user_id" => auth()->user()->id,
                "patient_id" => $patient->id,
                "room_type" => $array_patient['room_type'],
                "origin" => ($array_patient['origin']) ? $array_patient['origin'] : '',
                "admission_date" => $array_patient['date_entry'],
                "admission_time" => $array_patient['time_entry'],
                "departure_date" => $array_patient['date_exit'],
                "departure_time" => $array_patient['time_exit'],
                "quotation_id" => $quotation_id,
                "status" => 0,
            ]
        );

        foreach ($array_professional as $professional){
            AppointmentProfessional::create([
                'appointment_id' => $appointment['id'],
                'medical_professional_id' => $professional['id']
            ]);
        }


    }
}
