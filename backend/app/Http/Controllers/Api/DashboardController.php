<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function overview()
    {
        return response()->json([
            'total_orders' => Order::count(),
            'active_orders' => Order::whereIn('status', ['pending', 'in_production'])->count(),
            'inventory_items' => Product::count(),
            'customers' => Customer::count(),
            'recent_orders' => Order::with('customer')->orderByDesc('id')->limit(5)->get(),
            'low_stock' => Product::whereColumn('stock', '<=', 'low_stock_threshold')->limit(5)->get(),
        ]);
    }

    public function charts()
    {
        $now = Carbon::now();
        $months = collect(range(0, 11))->map(function ($i) use ($now) {
            return $now->copy()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $productionBars = $months->map(function ($ym) {
            $start = Carbon::parse($ym.'-01');
            $end = $start->copy()->endOfMonth();
            return DB::table('productions')
                ->whereBetween('created_at', [$start, $end])
                ->sum('quantity');
        });

        $salesLine = $months->map(function ($ym) {
            $start = Carbon::parse($ym.'-01');
            $end = $start->copy()->endOfMonth();
            return DB::table('orders')
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_amount');
        });

        $topSelling = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as qty'))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        return response()->json([
            'months' => $months,
            'production' => $productionBars,
            'sales' => $salesLine,
            'topSelling' => $topSelling,
        ]);
    }
}

