<?php

namespace App\Services\Ventas\Contracts;

use App\Models\User;
use App\Models\Venta;
use App\Services\Ventas\DTOs\VentaResponse;

interface VentaServiceInterface
{
    /**
     * Crea una nueva venta
     */
    public function crearVenta(int $usuarioId, int $sucursalId, ?int $clienteId = null, ?int $mesaId = null, ?string $tipo = null): VentaResponse;

    /**
     * Procesa el cobro de una venta
     */
    public function cobrarVenta(Venta $venta, array $metodosSeleccionados, float $totalAcumulado, float $subtotalConDescuento, float $descuentoSaldo = 0): VentaResponse;

    /**
     * Cierra y finaliza una venta
     */
    public function cerrarVenta(Venta $venta): VentaResponse;

    /**
     * Elimina una venta
     */
    public function eliminarVenta(Venta $venta): VentaResponse;

    /**
     * Cambia el cliente de una venta
     */
    public function cambiarClienteVenta(Venta $venta, User $cliente): VentaResponse;

    /**
     * Agrega usuario manual a la venta
     */
    public function agregarUsuarioManual(Venta $venta, string $usuarioManual): VentaResponse;

    /**
     * Edita el descuento manual de la venta
     */
    public function editarDescuento(Venta $venta, float $descuento): VentaResponse;

    /**
     * Envía pedido a cocina
     */
    public function enviarACocina(Venta $venta): VentaResponse;

    /**
     * Valida si una venta puede ser modificada
     */
    public function validarVentaModificable(Venta $venta): VentaResponse;

    /**
     * Valida si una venta puede ser cobrada
     */
    public function validarVentaCobrable(Venta $venta, array $metodosSeleccionados, float $totalAcumulado, float $subtotalConDescuento): VentaResponse;
}
