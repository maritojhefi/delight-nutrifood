<?php

namespace App\Services\Ventas\Contracts;

use App\Models\Venta;
use App\Models\Saldo;
use App\Services\Ventas\DTOs\VentaResponse;

interface SaldoServiceInterface
{
    /**
     * Registra un nuevo saldo para un cliente
     */
    public function registrarSaldo(Venta $venta, float $monto, string $detalle, int $tipo, bool $esDeuda = false): VentaResponse;

    /**
     * Anula o reactiva un saldo
     */
    public function anularSaldo(Saldo $saldo): VentaResponse;

    /**
     * Crea un saldo automático durante el proceso de cobranza
     */
    public function crearSaldoCobranza(int $clienteId, int $historialVentaId, int $cajaId, float $monto, bool $aFavorCliente, int $atendidoPor): VentaResponse;

    /**
     * Calcula el descuento máximo permitido por saldo del cliente
     */
    public function calcularMaximoDescuentoSaldo(Venta $venta): float;

    /**
     * Valida si se puede aplicar un descuento de saldo
     */
    public function validarDescuentoSaldo(Venta $venta, float $descuento): VentaResponse;
}
