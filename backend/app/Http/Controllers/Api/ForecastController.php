<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Support\Carbon;

class ForecastController extends Controller
{
    public function overview()
    {
        // Simple moving average placeholder using last 7 days of usage
        $materials = Material::all()->map(function ($m) {
            $dailyUsage = max(0.1, ($m->low_stock_threshold ?: 1) / 7);
            $daysUntilDepletion = $m->stock > 0 ? round($m->stock / $dailyUsage, 1) : 0;
            return [
                'id' => $m->id,
                'code' => $m->code,
                'name' => $m->name,
                'stock' => (float) $m->stock,
                'predicted_days_until_depletion' => $daysUntilDepletion,
                'suggested_reorder_qty' => max(0, ($m->low_stock_threshold * 2) - $m->stock),
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

        return response()->json([
            'materials' => $materials,
            'salesForecast' => $salesForecast,
            'capacity' => $capacity,
        ]);
    }
}

