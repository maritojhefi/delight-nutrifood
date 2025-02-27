<?php

namespace App\Providers;

use App\Models\Saldo;
use Livewire\Livewire;
use App\Charts\SampleChart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckIfCajaOpen;
use App\Observers\SaldoObserver;

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
    }
}
