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

    public $atendidoPor;
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
        if ($this->atendidoPor != null) {
            return $this->hasMany(Historial_venta::class)->where('usuario_id', $this->atendidoPor);
        } else {
            return $this->hasMany(Historial_venta::class);
        }
    }
    public function saldos()
    {
        if ($this->atendidoPor != null) {
            return $this->hasMany(Saldo::class, 'caja_id')->where('atendido_por', $this->atendidoPor);
        } else {
            return $this->hasMany(Saldo::class, 'caja_id');
        }
    }
    public function saldosPagadosSinVenta()
    {
        if ($this->atendidoPor != null) {
            return $this->hasMany(Saldo::class, 'caja_id')->where('historial_ventas_id', null)->where('es_deuda', false)->where('anulado', false)->where('atendido_por', $this->atendidoPor);
        } else {
            return $this->hasMany(Saldo::class, 'caja_id')->where('historial_ventas_id', null)->where('es_deuda', false)->where('anulado', false);
        }
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

    public function ingresosPorCajeroDeVentas()
    {
        return $this->ventas()
            ->join('users', 'historial_ventas.usuario_id', '=', 'users.id') // Relaciona con la tabla users
            ->select('usuario_id', 'users.name as cajero_nombre', DB::raw('SUM(total_pagado) as total_pagado'))
            ->groupBy('usuario_id', 'users.name') // Agrupa por ID del usuario y su nombre
            ->get();
    }

    public function ingresosPorCajeroPagoDeSaldos()
    {
        return $this->saldosPagadosSinVenta()
            ->join('users', 'saldos.atendido_por', '=', 'users.id') // Relaciona con la tabla users
            ->select('atendido_por as usuario_id', 'users.name as cajero_nombre', DB::raw('SUM(monto) as monto'))
            ->groupBy('atendido_por', 'users.name') // Agrupa por ID del usuario y su nombre
            ->get();
    }

    public function ingresosTotalesPorCajero(): array
    {
        $ventas = $this->ingresosPorCajeroDeVentas();
        $saldos = $this->ingresosPorCajeroPagoDeSaldos();

        $ingresosTotales = [];

        // Procesar ingresos de ventas
        foreach ($ventas as $venta) {
            $cajeroId = $venta->usuario_id;
            $nombre = $venta->cajero_nombre;
            $monto = $venta->total_pagado;

            if (isset($ingresosTotales[$cajeroId])) {
                $ingresosTotales[$cajeroId]['monto'] += $monto;
            } else {
                $ingresosTotales[$cajeroId] = [
                    'nombre' => $nombre,
                    'monto' => $monto,
                ];
            }
        }

        // Procesar ingresos de saldos pagados
        foreach ($saldos as $saldo) {
            $cajeroId = $saldo->usuario_id;
            $nombre = $saldo->cajero_nombre;
            $monto = $saldo->monto;

            if (isset($ingresosTotales[$cajeroId])) {
                $ingresosTotales[$cajeroId]['monto'] += $monto;
            } else {
                $ingresosTotales[$cajeroId] = [
                    'nombre' => $nombre,
                    'monto' => $monto,
                ];
            }
        }

        return $ingresosTotales;
    }
    public function generarGraficoIngresosPorCajero()
    {
        return CajaReporteHelper::graficoIngresosPorCajero($this->ingresosTotalesPorCajero());
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
    public function arrayProductosVendidos($cajeroId = null)
    {
        return CajaReporteHelper::arrayProductosVendidosRanking($this->id, $cajeroId);
    }
    public function urlGraficoComposicionIngresos(): string
    {
        return CajaReporteHelper::graficoComposicionIngresos($this->totalIngresoAbsoluto(), $this->ingresoVentasPOS(), $this->totalSaldosPagadosSinVenta());
    }
    public function urlGraficoProductosVendidos($cajeroId = null): string
    {
        return CajaReporteHelper::urlGraficoProductosVendidos($this->arrayProductosVendidos($cajeroId));
    }
}
