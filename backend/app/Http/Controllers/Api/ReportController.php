<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $sales = DB::table('orders')
            ->when($from && $to, fn($q) => $q->whereBetween('created_at', [$from, $to]))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        return response()->json($sales);
    }

    public function inventory()
    {
        $items = DB::table('products')->select('id','name','stock','low_stock_threshold')->get();
        return response()->json($items);
    }

    public function production()
    {
        $rows = DB::table('productions')->select('id','batch_number','quantity','progress_percent','status')->get();
        return response()->json($rows);
    }

    public function performance()
    {
        $summary = [
            'on_time_rate' => 92,
            'utilization' => 76,
            'defect_rate' => 1.5,
        ];
        return response()->json($summary);
    }

    public function export(Request $request)
    {
        $type = $request->query('type', 'sales');
        $format = $request->query('format', 'pdf'); // pdf|csv|xlsx

        if ($format === 'pdf') {
            $data = ['title' => ucfirst($type).' Report', 'rows' => []];
            $pdf = Pdf::loadView('reports.generic', $data);
            return $pdf->download($type.'-report.pdf');
        }

        $rows = DB::table('orders')->select('id','customer_id','total_amount','created_at')->orderByDesc('id')->limit(100)->get()->map(fn($r)=>(array)$r);
        $tempPath = storage_path("app/{$type}-report.".($format==='xlsx'?'xlsx':'csv'));
        SimpleExcelWriter::create($tempPath)->addRows($rows->toArray());
        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}

