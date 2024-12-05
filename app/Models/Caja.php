<?php

namespace App\Models;

use App\Helpers\CajaReporteHelper;
use App\Models\Sucursale;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = [
        'acumulado',
        'entrada',
        'sucursale_id',
        'estado'

    ];
    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function ventas()
    {
        return $this->hasMany(Historial_venta::class);
    }
    public function saldos()
    {
        return $this->hasMany(Saldo::class, 'caja_id');
    }
    public function saldosPagadosSinVenta()
    {
        return $this->hasMany(Saldo::class, 'caja_id')->where('historial_ventas_id', null)->where('es_deuda', false)->where('anulado', false);
    }
    private function calcularIngresosPorMetodoPago($coleccion): array
    {
        $acumuladoPorMetodoPago = [];

        foreach ($coleccion as $item) {
            foreach ($item->metodosPagos as $metodo) {
                $monto = $metodo->pivot->monto;
                $nombre = $metodo->nombre_metodo_pago;

                if (isset($acumuladoPorMetodoPago[$nombre])) {
                    $acumuladoPorMetodoPago[$nombre] += $monto;
                } else {
                    $acumuladoPorMetodoPago[$nombre] = $monto;
                }
            }
        }

        return $acumuladoPorMetodoPago;
    }
    public function ingresosPorMetodoPagoDeVentas(): array
    {
        return $this->calcularIngresosPorMetodoPago($this->ventas);
    }

    public function ingresosPorMetodoPagoDeSaldos(): array
    {
        return $this->calcularIngresosPorMetodoPago($this->saldosPagadosSinVenta);
    }
    public function ingresosTotalesPorMetodoPago(): array
    {
        $ventas = $this->ingresosPorMetodoPagoDeVentas();
        $saldos = $this->ingresosPorMetodoPagoDeSaldos();

        return array_merge_recursive(
            array_map('floatval', $ventas),
            array_map('floatval', $saldos)
        );
    }


    public function totalDescuentos(): float
    {
        return floatval($this->ventas()->sum('total_descuento'));
    }

    public function ingresoVentasPOS(): float
    {
        return floatval($this->ventas()->sum('total_pagado'));
    }
    public function totalIngresoAbsoluto(): float
    {
        return floatval($this->ingresoVentasPOS() + $this->totalSaldosPagadosSinVenta());
    }
    public function totalSaldoExcedentes(): float
    {
        return floatval($this->ventas()->where('a_favor_cliente', true)->sum('saldo_monto'));
    }

    public function totalPuntos(): float
    {
        return floatval($this->ventas()->sum('puntos'));
    }

    public function totalSaldosPagadosSinVenta(): float
    {
        return floatval($this->saldosPagadosSinVenta()->sum('monto'));
    }
    public function generarGraficoIngresosPorMetodoPago(): string
    {
        return CajaReporteHelper::graficoIngresosPorMetodo($this->ingresosTotalesPorMetodoPago());
    }
    public function arrayProductosVendidos()
    {
        return CajaReporteHelper::arrayProductosVendidosRanking($this->id);
    }
    public function urlGraficoComposicionIngresos(): string
    {
        return CajaReporteHelper::graficoComposicionIngresos($this->totalIngresoAbsoluto(), $this->ingresoVentasPOS(), $this->totalSaldosPagadosSinVenta());
    }
    public function urlGraficoProductosVendidos(): string
    {
        return CajaReporteHelper::urlGraficoProductosVendidos($this->arrayProductosVendidos());
    }
}
