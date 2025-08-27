<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\InventoryMovement;
use Illuminate\Support\Carbon;

class ForecastController extends Controller
{
    public function overview()
    {
        $windowDays = 14;
        $materials = Material::all()->map(function ($m) use ($windowDays) {
            $from = Carbon::now()->subDays($windowDays);
            $usage = InventoryMovement::where('item_type', 'material')
                ->where('item_id', $m->id)
                ->where('type', 'out')
                ->where('created_at', '>=', $from)
                ->get()
                ->groupBy(fn($r) => Carbon::parse($r->created_at)->toDateString())
                ->map(fn($rows) => (float) $rows->sum('quantity'));

            $days = range(0, $windowDays - 1);
            $series = collect($days)->map(function ($d) use ($usage) {
                $date = Carbon::now()->subDays($d)->toDateString();
                return (float) ($usage[$date] ?? 0);
            })->reverse()->values();

            $avgDailyConsumption = max(0.01, round(($series->sum() / max(1, $windowDays)), 3));
            $daysUntilDepletion = $m->stock > 0 ? round($m->stock / $avgDailyConsumption, 1) : 0;
            $reorderPointDays = 7; // lead time buffer in days
            $reorderPointQty = $avgDailyConsumption * $reorderPointDays;
            $suggested = max(0, ($reorderPointQty * 2) - (float) $m->stock);

            return [
                'id' => $m->id,
                'code' => $m->code,
                'name' => $m->name,
                'stock' => (float) $m->stock,
                'avg_daily_consumption' => $avgDailyConsumption,
                'predicted_days_until_depletion' => $daysUntilDepletion,
                'suggested_reorder_qty' => round($suggested, 2),
            ];
        });

        $months = collect(range(0, 2))->map(fn($i) => Carbon::now()->addMonths($i)->format('M Y'));
        $salesForecast = [
            'labels' => $months,
            'data' => [
                rand(50, 80), rand(60, 100), rand(70, 120)
            ],
        ];

        $capacity = [
            'labels' => ['Plant A', 'Plant B', 'Plant C'],
            'data' => [72, 68, 81],
        ];

        // Identify materials that will deplete within 7 days for alerts
        $alerts = $materials->filter(fn($m) => ($m['predicted_days_until_depletion'] ?? 0) <= 7)->values();

        return response()->json([
            'materials' => $materials,
            'salesForecast' => $salesForecast,
            'capacity' => $capacity,
            'alerts' => $alerts,
        ]);
    }
}

