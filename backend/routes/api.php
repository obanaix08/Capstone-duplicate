<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ProductionController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ForecastController;
use App\Http\Controllers\Api\CustomerController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard/overview', [DashboardController::class, 'overview']);
    Route::get('/dashboard/charts', [DashboardController::class, 'charts']);

    Route::apiResource('inventory', InventoryController::class);
    Route::get('inventory/low-stock', [InventoryController::class, 'lowStock']);

    Route::apiResource('productions', ProductionController::class);

    Route::apiResource('orders', OrderController::class);
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus']);

    Route::get('reports/sales', [ReportController::class, 'sales']);
    Route::get('reports/inventory', [ReportController::class, 'inventory']);
    Route::get('reports/production', [ReportController::class, 'production']);
    Route::get('reports/performance', [ReportController::class, 'performance']);
    Route::get('reports/export', [ReportController::class, 'export']);

    Route::get('forecasting/overview', [ForecastController::class, 'overview']);

    Route::apiResource('customers', CustomerController::class);
});

