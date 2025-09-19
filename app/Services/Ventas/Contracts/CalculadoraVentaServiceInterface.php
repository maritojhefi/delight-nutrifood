<?php

namespace App\Services\Ventas\Contracts;

use App\Models\Venta;
use App\Services\Ventas\DTOs\VentaCalculosData;

interface CalculadoraVentaServiceInterface
{
    /**
     * Calcula todos los valores de una venta
     */
    public function calcularVenta(Venta $venta, ?float $descuentoSaldo = 0): VentaCalculosData;

    /**
     * Calcula el descuento máximo de saldo permitido
     */
    public function calcularMaximoDescuentoSaldo(Venta $venta): float;

    /**
     * Calcula el saldo resultante (a favor o deuda del cliente)
     */
    public function calcularSaldoResultante(float $totalAcumulado, float $subtotalConDescuento): array;

    /**
     * Actualiza los totales de la venta en la base de datos
     */
    public function actualizarTotalesVenta(Venta $venta): void;
}
