<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Models\Material;
use App\Notifications\LowStockAlert;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Material::created(function(Material $m) {
            if ($m->stock <= $m->low_stock_threshold) {
                Notification::route('mail', config('mail.from.address'))
                    ->notify(new LowStockAlert('material', $m->name, (float)$m->stock, (float)$m->low_stock_threshold));
            }
        });

        Material::updated(function(Material $m) {
            if ($m->wasChanged('stock') || $m->wasChanged('low_stock_threshold')) {
                if ($m->stock <= $m->low_stock_threshold) {
                    Notification::route('mail', config('mail.from.address'))
                        ->notify(new LowStockAlert('material', $m->name, (float)$m->stock, (float)$m->low_stock_threshold));
                }
            }
        });
    }
}
