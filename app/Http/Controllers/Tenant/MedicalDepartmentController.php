<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\MedicalDepartmentRequest;
use App\Http\Resources\Tenant\MedicalDepartmentCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\MedicalDepartmentResource;
use App\Models\Tenant\MedicalDepartment;
use Illuminate\Http\Request;

class MedicalDepartmentController extends Controller
{
    public function index()
    {
        return view('tenant.medical_departments.index');
    }

    public function records()
    {
        $records = MedicalDepartment::all();

        return new MedicalDepartmentCollection($records);
    }

    public function record($id)
    {
        $record = new MedicalDepartmentResource(MedicalDepartment::findOrFail($id));

        return $record;
    }

    public function tables()
    {
        return compact('medical_services'); // nothing
    }

    public function store(MedicalDepartmentRequest $request)
    {
        $id = $request->input('id');

        $medical_department = MedicalDepartment::firstOrNew(['id' => $id]);
        $medical_department->name = $request->input('name');
        $medical_department->description = $request->input('description');
        $medical_department->active = $request->input('active');
        $medical_department->save();


        return [
            'success' => true,
            'message' => ($id) ? 'Departamento Médico actualizado' : 'Departamento Médico registrado'
        ];
    }

    public function destroy($id)
    {
        $medical_department = MedicalDepartment::findOrFail($id);
        $medical_department->delete();

        return [
            'success' => true,
            'message' => 'Departamento Médico eliminado con éxito'
        ];
    }

    public function checkStatus(Request $request){

        $medical_department = MedicalDepartment::findOrFail($request->id);
        $medical_department->active = $request->active;
        $medical_department->save();

        return [
            'success' => true,
            'message' => ($medical_department->active == 1) ? 'Departamento Médico Habilitado' : 'Departamento Médico Deshabilitado'
        ];

    }
}
