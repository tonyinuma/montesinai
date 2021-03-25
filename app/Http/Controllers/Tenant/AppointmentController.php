<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\AppointmentRequest;
use App\Http\Requests\Tenant\PatientAppointmentRequest;
use App\Http\Requests\Tenant\ProfessionalAppointmentRequest;
use App\Http\Requests\Tenant\QuotationAppointmentRequest;
use App\Http\Resources\Tenant\AppointmentCollection;
use App\Http\Resources\Tenant\AppointmentResource;
use App\Models\Tenant\Appointment;
use App\Http\Controllers\Controller;
use App\Models\Tenant\AppointmentProfessional;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Doctor;
use App\Models\Tenant\MedicalProfessional;
use App\Models\Tenant\MedicalService;
use App\Models\Tenant\Patient;
use App\Models\Tenant\RoomType;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('tenant.appointments.index');
    }

    public function records()
    {
        $records = Appointment::all();
        return new AppointmentCollection($records);
    }

    public function record($id)
    {
        $record = new AppointmentResource(Appointment::findOrFail($id));
        return $record;
    }

    public function tables()
    {
        $patients = Patient::whereNull('deleted_at')->orderBy('lastname')->get();
        $doctors = Doctor::whereNull('deleted_at')->orderBy('lastname')->get();
        $medical_services = MedicalService::whereNull('deleted_at')->orderBy('name')->get();
        $professionals = MedicalProfessional::where('availability', 1)->whereNull('deleted_at')->orderBy('name')->get();

        $identity_document_types = IdentityDocumentType::whereIn('id', ['1', '4', '7'])->get();

        $sexs = [
            ['id' => 'M', 'description' => 'Masculino'],
            ['id' => 'F', 'description' => 'Femenino'],
        ];

        $civil_status = [
            ['id' => 'S', 'description' => 'Soltero/a'],
            ['id' => 'C', 'description' => 'Casado/a'],
            ['id' => 'V', 'description' => 'Viudo/a'],
            ['id' => 'D', 'description' => 'Divorciado/a'],
        ];

        $room_types = RoomType::whereNull('deleted_at')->get();

        $specialities = [
            ['id' => 1, 'name' => 'ENFERMERO/A'],
            ['id' => 2, 'name' => 'ANESTECIOLOGO/A'],
            ['id' => 3, 'name' => 'TERAPEUTA'],
            ['id' => 4, 'name' => 'PSICOLOGO/A'],
        ];

        $states = [
            ['id' => 0, 'description' => 'Cotizado'],
            ['id' => 1, 'description' => 'Aprobado'],
            ['id' => 2, 'description' => 'En curso'],
            ['id' => 3, 'description' => 'Finalizado'],
            ['id' => 4, 'description' => 'Descartado'],
        ];

        $api_service_token = config('configuration.api_service_token');

        return compact('patients', 'doctors', 'medical_services', 'specialities',
            'identity_document_types', 'sexs', 'civil_status', 'room_types', 'states', 'professionals', 'api_service_token');
    }

    public function store(AppointmentRequest $request)
    {
        $id = $request->input('id'); // To Update Appointment
        $identity_document_type = $request->input('identity_document_type_id');

        if ($identity_document_type) {
            $patient = Patient::firstOrNew(['id' => $request->input('patient_id')]);
            $patient->identity_document_type_id = $request->input('identity_document_type_id');
            $patient->document_number = $request->input('number');
            $patient->name = $request->input('name');
            $patient->sex = $request->input('sex');
            $patient->occupation = $request->input('occupation');
            $patient->age = $request->input('age');
            $patient->marital_status = $request->input('civil_status');
            $patient->nationality = $request->input('nationality');
            $patient->mobile_phone = $request->input('mobile_phone');
            $patient->save();
        }

        $appointment = Appointment::firstOrNew(['id' => $id]);
        $appointment->allocator_user_id = auth()->user()->id;
        $appointment->patient_id = $request->input('patient_id') ? $request->input('patient_id') : $patient->id;
        $appointment->room_type = $request->input('room_type');
        $appointment->room_number = $request->input('room_number');
        $appointment->origin = $request->input('origin');
        $appointment->admission_date = $request->input('admission_date');
        $appointment->admission_time = $request->input('admission_time');
        $appointment->departure_date = $request->input('departure_date');
        $appointment->departure_time = $request->input('departure_time');
        $appointment->observation = $request->input('observation');
        $appointment->status = $request->input('status');
        $appointment->save();


        return [
            'success' => true,
            'appointment_id' => $appointment->id,
            'message' => ($id) ? 'Cita actualizada' : 'Cita registrada'
        ];
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return [
            'success' => true,
            'message' => 'Cita eliminada con éxito'
        ];
    }

    public function validatePatientAppointment(PatientAppointmentRequest $request)
    {
        return [
            'success' => true
        ];
    }

    public function professionalsBySpeciality($speciality_id)
    {
        $professionals = MedicalProfessional::where('availability', 1)->where('medical_speciality_id', $speciality_id)
            ->whereNull('deleted_at')->orderBy('name')->get();

        return response()->json(['success' => true, 'professionals' => $professionals]);
    }

    public function validateProfessionalAppointment(ProfessionalAppointmentRequest $request)
    {
        return [
            'success' => true
        ];
    }

    public function dataEdit($id)
    {
        $appointment = Appointment::where('quotation_id', $id)->first();
        return response()->json(['success' => true, 'appointment' => $appointment]);
    }

    public function professionalDataEdit($id)
    {
        $appointment = Appointment::where('quotation_id', $id)->first();
        $appointment_professionals = AppointmentProfessional::where('appointment_id', $appointment->id)->where('status', '1')->get();
        $data = [];

        foreach ($appointment_professionals as $appointment_professional) {
            array_push($data, $appointment_professional->medical_professional);
        }

        return response()->json(['success' => true, 'appointment_professionals' => $data, 'appointment_id' => $appointment->id]);
    }

    public function updatePatientAppointment(PatientAppointmentRequest $request)
    {

        $patient = Patient::firstOrNew(['id' => $request->patient_id]);
        $patient->identity_document_type_id = $request->input('identity_document_type_id');
        $patient->document_number = $request->input('number');
        $patient->name = $request->input('name');
        $patient->occupation = $request->input('occupation');
        $patient->mobile_phone = $request->input('mobile_phone');
        $patient->nationality = $request->input('nationality');
        $patient->save();

        $appointment = Appointment::firstOrNew(['id' => $request->appointment_id]);
        $appointment->patient_id = $patient->id;
        $appointment->room_type = $request->input('room_type');
        $appointment->origin = $request->input('origin');
        $appointment->admission_date = $request->input('date_entry');
        $appointment->admission_time = $request->input('time_entry');
        $appointment->departure_date = $request->input('date_exit');
        $appointment->departure_time = $request->input('time_exit');
        $appointment->save();

        return [
            'success' => true
        ];
    }

    public function updateProfessionalAppointment(ProfessionalAppointmentRequest $request)
    {
        AppointmentProfessional::where('appointment_id', $request->appointment_id)->delete();
        $data_update = json_decode(json_encode($request->professionals_data), FALSE);

        foreach ($data_update as $professional){
            AppointmentProfessional::create([
                'appointment_id' => $request->appointment_id,
                'medical_professional_id' => $professional->id
            ]);
        }

        return [
            'success' => true
        ];
    }


}
