<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Saldo;
use App\Models\Venta;
use Livewire\Livewire;
use App\Models\Producto;
use App\Charts\SampleChart;
use App\Models\Historial_venta;
use App\Observers\UserObserver;
use App\Observers\SaldoObserver;
use App\Observers\VentaObserver;
use App\Observers\ProductoObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckIfCajaOpen;
use App\View\Composers\UserDataComposer;
use App\Observers\HistorialVentaObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Saldo::observe(SaldoObserver::class);
        Venta::observe(VentaObserver::class);
        Producto::observe(ProductoObserver::class);
        Historial_venta::observe(HistorialVentaObserver::class);
        User::observe(UserObserver::class);
        View::composer('*', UserDataComposer::class);
    }
}
