<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\DoctorRequest;
use App\Http\Resources\Tenant\DoctorCollection;
use App\Http\Resources\Tenant\DoctorResource;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Doctor;
use App\Http\Controllers\Controller;
use App\Models\Tenant\MedicalDepartment;

class DoctorController extends Controller
{
    public function index()
    {
        return view('tenant.doctors.index');
    }

    public function records()
    {
        $records = Doctor::all();

        return new DoctorCollection($records);
    }

    public function record($id)
    {
        $record = new DoctorResource(Doctor::findOrFail($id));

        return $record;
    }

    public function tables()
    {
        $identity_document_types = IdentityDocumentType::whereActive()->get();
        $medical_departments = MedicalDepartment::whereNull('deleted_at')->get();

        return compact('modules', 'identity_document_types', 'medical_departments');
    }

    public function store(DoctorRequest $request)
    {
        $id = $request->input('id');

        if (!$id)  //VALIDAR DNI DISPONIBLE
        {
            $verify = Doctor::where('document_number', $request->input('document_number'))->first();
            if ($verify) {
                return [
                    'success' => false,
                    'message' => 'El Documento ya se encuentra Registrado'
                ];
            }
        }

        $doctor = Doctor::firstOrNew(['id' => $id]);
        $doctor->identity_document_type_id = $request->input('identity_document_type_id');
        $doctor->name = $request->input('name');
        $doctor->medical_department_id = $request->input('medical_department_id');
        $doctor->document_number = $request->input('document_number');
        $doctor->mobile_phone = $request->input('mobile_phone');
        $doctor->save();


        return [
            'success' => true,
            'message' => ($id) ? 'Doctor actualizado' : 'Doctor registrado'
        ];
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return [
            'success' => true,
            'message' => 'Doctor eliminado con éxito'
        ];
    }
}
