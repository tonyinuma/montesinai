<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\DoctorRequest;
use App\Http\Requests\Tenant\MedicalProfessionalRequest;
use App\Http\Resources\Tenant\MedicalProfessionalCollection;
use App\Http\Resources\Tenant\MedicalProfessionalResource;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Http\Controllers\Controller;
use App\Models\Tenant\MedicalDepartment;
use App\Models\Tenant\MedicalProfessional;
use Illuminate\Http\Request;

class MedicalProfessionalController extends Controller
{
    public function index()
    {
        return view('tenant.medical_professionals.index');
    }

    public function records()
    {
        $records = MedicalProfessional::all();

        return new MedicalProfessionalCollection($records);
    }

    public function record($id)
    {
        $record = new MedicalProfessionalResource(MedicalProfessional::findOrFail($id));

        return $record;
    }

    public function tables()
    {
        $identity_document_types = IdentityDocumentType::whereActive()->get();
        $medical_departments = MedicalDepartment::whereNull('deleted_at')->get();

        $medical_specialities = [
            ['id' => 1, 'name' => 'ENFERMERO/A'],
            ['id' => 2, 'name' => 'ANESTECIOLOGO/A'],
            ['id' => 3, 'name' => 'TERAPEUTA'],
            ['id' => 4, 'name' => 'PSICOLOGO/A'],
        ];

        return compact('modules', 'identity_document_types', 'medical_departments', 'medical_specialities');
    }

    public function store(MedicalProfessionalRequest $request)
    {
        $id = $request->input('id');

        if (!$id)  //VALIDAR DNI DISPONIBLE
        {
            $verify = MedicalProfessional::where('document_number', $request->input('document_number'))->first();
            if ($verify) {
                return [
                    'success' => false,
                    'message' => 'El Documento ya se encuentra Registrado'
                ];
            }
        }

        $medical_professional = MedicalProfessional::firstOrNew(['id' => $id]);
        $medical_professional->name = $request->input('name');
        $medical_professional->identity_document_type_id = $request->input('identity_document_type_id');
        $medical_professional->medical_department_id = $request->input('medical_department_id');
        $medical_professional->document_number = $request->input('document_number');
        $medical_professional->mobile_phone = $request->input('mobile_phone');
        $medical_professional->medical_speciality_id = $request->input('medical_speciality_id');
        $medical_professional->availability = $request->input('availability');
        $medical_professional->save();


        return [
            'success' => true,
            'message' => ($id) ? 'Profesional actualizado' : 'Profesional registrado'
        ];
    }

    public function destroy($id)
    {
        $medical_professional = MedicalProfessional::findOrFail($id);
        $medical_professional->delete();

        return [
            'success' => true,
            'message' => 'Profesional eliminado con éxito'
        ];
    }

    public function checkStatus(Request $request)
    {
        $medical_professional = MedicalProfessional::findOrFail($request->id);
        $medical_professional->availability = $request->availability;
        $medical_professional->save();

        return [
            'success' => true,
            'message' => ($medical_professional->availability == 1) ? 'Profesional Médico Disponible' : 'Profesional Médico No Disponible'
        ];
    }
}
