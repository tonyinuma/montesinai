<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class ItemExpireExport implements  FromView, ShouldAutoSize
{
    use Exportable;

    public function records($records) {
        $this->records = $records;

        return $this;
    }

    public function view(): View {
        return view('tenant.items.exports.items-expire', [
            'records'=> $this->records,
            'today' => Carbon::now()->format('Y-m-d'),
            'range_due' => Carbon::now()->addMonths(3)->format('Y-m-d'),
        ]);
    }


}
