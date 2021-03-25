<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\MedicalServiceRequest;
use App\Http\Resources\Tenant\MedicalServiceCollection;
use App\Http\Resources\Tenant\MedicalServiceResource;
use App\Models\Tenant\MedicalDepartment;
use App\Models\Tenant\MedicalService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalServiceController extends Controller
{
    public function index()
    {
        return view('tenant.medical_services.index');
    }

    public function records()
    {
        $records = MedicalService::all();

        return new MedicalServiceCollection($records);
    }

    public function record($id)
    {
        $record = new MedicalServiceResource(MedicalService::findOrFail($id));

        return $record;
    }

    public function tables()
    {
        $medical_department = MedicalDepartment::all();

        return compact('medical_department'); // nothing
    }

    public function store(MedicalServiceRequest $request)
    {
        $id = $request->input('id');

        $medical_service = MedicalService::firstOrNew(['id' => $id]);
        $medical_service->name = $request->input('name');
        $medical_service->description = $request->input('description');
        $medical_service->suggested_price = $request->input('suggested_price');
        $medical_service->status = $request->input('status');
        $medical_service->save();


        return [
            'success' => true,
            'message' => ($id) ? 'Servicio Médico actualizado' : 'Servicio Médico registrado'
        ];
    }

    public function destroy($id)
    {
        $medical_service = MedicalService::findOrFail($id);
        $medical_service->delete();

        return [
            'success' => true,
            'message' => 'Servicio médico eliminado con éxito'
        ];
    }

    public function checkStatus(Request $request){

        $medical_service = MedicalService::findOrFail($request->id);
        $medical_service->status = $request->status;
        $medical_service->save();

        return [
            'success' => true,
            'message' => ($medical_service->status == 1) ? 'Servicio Médico Habilitado' : 'Servicio Médico Deshabilitado'
        ];

    }
}
