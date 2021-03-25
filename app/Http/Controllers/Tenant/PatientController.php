<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\PatientRequest;
use App\Http\Resources\Tenant\PatientCollection;
use App\Http\Resources\Tenant\PatientResource;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Patient;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    public function index()
    {
        return view('tenant.patients.index');
    }

    public function records()
    {
        $records = Patient::all();

        return new PatientCollection($records);
    }

    public function record($id)
    {
        $record = new PatientResource(Patient::findOrFail($id));

        return $record;
    }

    public function recordByDocumentNumber($document_number)
    {
        $patient = Patient::where('document_number', $document_number)->whereNull('deleted_at')->first();

        if (!$patient) {
            return ['success' => false];
        }

        $data = new PatientResource($patient);
        return [
            'success' => true,
            'data' => $data
        ];

    }

    public function tables()
    {
        $sexs = [
            ['id' => 'M', 'description' => 'Masculino'],
            ['id' => 'F', 'description' => 'Femenino'],
        ];

        $civil_statuses = [
            ['id' => 'S', 'description' => 'Soltero/a'],
            ['id' => 'C', 'description' => 'Casado/a'],
            ['id' => 'V', 'description' => 'Viudo/a'],
            ['id' => 'D', 'description' => 'Divorciado/a'],
        ];

        $identity_document_types = IdentityDocumentType::whereActive()->get();

        return compact('modules', 'identity_document_types', 'sexs', 'civil_statuses');
    }

    public function store(PatientRequest $request)
    {
        $id = $request->input('id');

        if (!$id)  //VALIDAR DNI DISPONIBLE
        {
            $verify = Patient::where('document_number', $request->input('document_number'))->first();
            if ($verify) {
                return [
                    'success' => false,
                    'message' => 'El Documento ya se encuentra Registrado'
                ];
            }
        }

        $patient = Patient::firstOrNew(['id' => $id]);
        $patient->name = $request->input('name');
        $patient->identity_document_type_id = $request->input('identity_document_type_id');
        $patient->document_number = $request->input('number');
        $patient->mobile_phone = $request->input('mobile_phone');
        $patient->age = $request->input('age');
        $patient->occupation = $request->input('occupation');
        $patient->nationality = $request->input('nationality');
        $patient->nationality = $request->input('nationality');
        $patient->sex = $request->input('sex');
        $patient->marital_status = $request->input('civil_status');
        $patient->save();


        return [
            'success' => true,
            'message' => ($id) ? 'Paciente actualizado' : 'Paciente registrado'
        ];
    }

    public function destroy($id)
    {
        $user = Patient::findOrFail($id);
        $user->delete();

        return [
            'success' => true,
            'message' => 'Paciente eliminado con éxito'
        ];
    }
}
