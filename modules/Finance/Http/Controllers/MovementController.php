<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\GlobalPayment;
use App\Models\Tenant\Cash;
use App\Models\Tenant\BankAccount;
use App\Models\Tenant\Company;
use Modules\Finance\Traits\FinanceTrait; 
use Modules\Finance\Http\Resources\MovementCollection;
use Modules\Finance\Exports\MovementExport;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Tenant\Establishment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MovementController extends Controller
{ 

    use FinanceTrait;

    public function index(){

        return view('finance::movements.index');
    }


    public function records(Request $request)
    {

        // dd($request->all());
        $records = $this->getRecords($request->all(), GlobalPayment::class);
        
        return new MovementCollection($records->paginate(config('tenant.items_per_page')));

    }

    public function getRecords($request, $model){

        $data_of_period = $this->getDatesOfPeriod($request);
        $payment_type = $request['payment_type'];
        $destination_type = $request['destination_type'];
        
        $params = (object)[
            'date_start' => $data_of_period['d_start'],
            'date_end' => $data_of_period['d_end'],
        ];
        
        $records = $model::whereFilterPaymentType($params);

        return $records->latest();
    }

    
    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), GlobalPayment::class)->get();

        $pdf = PDF::loadView('finance::movements.report_pdf', compact("records", "company", "establishment"))->setPaper('a4', 'landscape');;

        $filename = 'Reporte_Movimientos_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }


    public function excel(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), GlobalPayment::class)->get();

        return (new MovementExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('Reporte_Movimientos_'.Carbon::now().'.xlsx');

    }

}
