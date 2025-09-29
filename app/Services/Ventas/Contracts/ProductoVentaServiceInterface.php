<?php

namespace App\Services\Ventas\Contracts;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Adicionale;
use App\Services\Ventas\DTOs\VentaResponse;
use Illuminate\Database\Eloquent\Collection;

interface ProductoVentaServiceInterface
{
    /**
     * Agrega un producto a la venta
     */
    public function agregarProducto(Venta $venta, Producto $producto, int $cantidad = 1): VentaResponse;

    /**
     * Elimina una unidad de un producto de la venta
     */
    public function eliminarUnoProducto(Venta $venta, Producto $producto): VentaResponse;

    /**
     * Elimina completamente un producto de la venta
     */
    public function eliminarProductoCompleto(Venta $venta, Producto $producto): VentaResponse;

    /**
     * Agrega un adicional a un producto específico
     */
    public function agregarAdicional(Venta $venta, Producto $producto, Adicionale $adicional, int $item): VentaResponse;

    /**
     * Elimina un item específico de un producto
     */
    public function eliminarItem(Venta $venta, Producto $producto, int $posicion): VentaResponse;

    /**
     * Actualiza los adicionales de un producto
     */
    public function actualizarAdicionales(Venta $venta, Producto $producto, string $operacion, ?int $cantidadEspecifica = null): VentaResponse;

    /**
     * Guarda observación de un producto
     */
    public function guardarObservacion(Venta $venta, Producto $producto, string $observacion): VentaResponse;

    /**
     * Agrega producto desde plan de usuario
     */
    public function agregarDesdeplan(Venta $venta, int $userId, int $planId, int $productoId): VentaResponse;

    /**
     * Agrega un producto a la venta, considerando adicionales y cantidad (caso uso cliente)
     */
    public function agregarProductoCliente(Venta $venta, Producto $producto, Collection $adicionales, int $cantidad): VentaResponse;

    /**
     * Obtener información detallada de los productos registrados para una venta
     */
    public function obtenerProductosVenta(Venta $venta_activa): VentaResponse;

    /**
     * Obtener información detallada de un producto registrado para una venta
     */
    public function obtenerProductoVentaIndividual(Venta $venta, $idProducto): VentaResponse;
}
