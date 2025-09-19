<?php

namespace App\Services\Ventas;

use App\Models\Venta;
use App\Helpers\CreateList;
use Illuminate\Support\Facades\DB;
use App\Services\Ventas\DTOs\VentaCalculosData;
use App\Services\Ventas\Contracts\CalculadoraVentaServiceInterface;

class CalculadoraVentaService implements CalculadoraVentaServiceInterface
{
    public function calcularVenta(Venta $venta, ?float $descuentoSaldo = 0): VentaCalculosData
    {
        $resultado = CreateList::crearlista($venta);
        
        $listaCuenta = $resultado[0];
        $subtotal = $resultado[1];
        $itemsCuenta = $resultado[2];
        $puntos = $resultado[3];
        $descuentoProductos = $resultado[4];
        
        $subtotalConDescuento = $subtotal - $descuentoProductos - $descuentoSaldo - $venta->descuento;

        return new VentaCalculosData(
            listaCuenta: $listaCuenta->toArray(),
            subtotal: $subtotal,
            itemsCuenta: $itemsCuenta,
            puntos: $puntos,
            descuentoProductos: $descuentoProductos,
            subtotalConDescuento: $subtotalConDescuento,
            descuentoSaldo: $descuentoSaldo,
            descuentoManual: $venta->descuento
        );
    }

    public function calcularMaximoDescuentoSaldo(Venta $venta): float
    {
        if (!$venta->cliente || !$venta->cliente->saldo) {
            return 0;
        }

        $calculos = $this->calcularVenta($venta);
        return min($calculos->subtotalConDescuento, abs((int) $venta->cliente->saldo));
    }

    public function calcularSaldoResultante(float $totalAcumulado, float $subtotalConDescuento): array
    {
        if ($totalAcumulado === $subtotalConDescuento) {
            return [
                'saldoAFavorCliente' => null,
                'montoSaldo' => 0
            ];
        } elseif ($totalAcumulado < $subtotalConDescuento) {
            return [
                'saldoAFavorCliente' => false, // deuda del cliente
                'montoSaldo' => $subtotalConDescuento - $totalAcumulado
            ];
        } else {
            return [
                'saldoAFavorCliente' => true, // a favor del cliente
                'montoSaldo' => $totalAcumulado - $subtotalConDescuento
            ];
        }
    }

    public function actualizarTotalesVenta(Venta $venta): void
    {
        $calculos = $this->calcularVenta($venta);
        
        DB::table('ventas')
            ->where('id', $venta->id)
            ->update([
                'total' => $calculos->subtotal - $calculos->descuentoProductos,
                'puntos' => $calculos->puntos
            ]);
    }
}
