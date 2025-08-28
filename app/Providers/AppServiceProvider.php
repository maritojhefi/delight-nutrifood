<?php

namespace App\Providers;

use App\Models\Saldo;
use Livewire\Livewire;
use App\Models\Producto;
use App\Charts\SampleChart;
use App\Observers\SaldoObserver;
use App\Observers\ProductoObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckIfCajaOpen;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Saldo::observe(SaldoObserver::class);
        Producto::observe(ProductoObserver::class);
    }
}
