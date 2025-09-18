<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Ventas\VentaService;
use App\Services\Ventas\SaldoService;
use App\Services\Ventas\StockService;
use App\Services\Ventas\ProductoVentaService;
use App\Services\Ventas\CalculadoraVentaService;
use App\Services\Ventas\Contracts\VentaServiceInterface;
use App\Services\Ventas\Contracts\SaldoServiceInterface;
use App\Services\Ventas\Contracts\StockServiceInterface;
use App\Services\Ventas\Contracts\ProductoVentaServiceInterface;
use App\Services\Ventas\Contracts\CalculadoraVentaServiceInterface;

class VentaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar interfaces y sus implementaciones
        $this->app->bind(CalculadoraVentaServiceInterface::class, CalculadoraVentaService::class);
        $this->app->bind(StockServiceInterface::class, StockService::class);
        $this->app->bind(SaldoServiceInterface::class, SaldoService::class);
        $this->app->bind(ProductoVentaServiceInterface::class, ProductoVentaService::class);
        $this->app->bind(VentaServiceInterface::class, VentaService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
