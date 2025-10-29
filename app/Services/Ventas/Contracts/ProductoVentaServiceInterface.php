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
     * Elimina un item específico de un producto_venta indentificandolo por su id pivote
     */
    public function eliminarItemPivotID(Venta $venta, int $producto_venta_id, int $posicion): VentaResponse;

    /**
     * Elimina un pedido por completo identificandolo por su id pivote
     */
    public function eliminarProductoCompletoCliente(Venta $venta, int $producto_venta_id);

    /**
     * Actualiza los adicionales de un producto
     */
    public function actualizarAdicionales(Venta $venta, Producto $producto, string $operacion, ?int $cantidadEspecifica = null): VentaResponse;

    /**
     * Guarda observación de un producto
     */
    public function guardarObservacion(Venta $venta, Producto $producto, string $observacion): VentaResponse;

    /**
     * Guarda observación de un producto_venta indentificandolo por su id pivote
     */
    public function guardarObservacionPivotID(int $producto_venta_id, string $observacion): VentaResponse;

    /**
     * Agrega producto desde plan de usuario
     */
    public function agregarDesdeplan(Venta $venta, int $userId, int $planId, int $productoId): VentaResponse;

    /**
     * Agrega un producto a la venta, considerando adicionales y cantidad (caso uso cliente)
     */
    public function agregarProductoCliente(Venta $venta, Producto $producto, Collection $adicionales, int $cantidad, ?string $observacion = null): VentaResponse;

    /**
     * Disminuye ordenes de un producto sin aceptar por parte del cliente
     */
    public function disminuirProductoCLiente(Venta $venta, int $producto_venta_id): VentaResponse;

    /**
     * Obtener información detallada de los productos registrados para una venta
     */
    public function obtenerProductosVenta(Venta $venta_activa): VentaResponse;

    /**
     * Obtener información detallada de un producto registrado para una venta
     */
    public function obtenerProductoVentaIndividual(Venta $venta, $idProducto): VentaResponse;

    /**
     * Obtener información detallada de una orden segun el indice y el identificador de producto_venta
     */
    public function obtenerOrdenPorIndice(Venta $venta, int $producto_venta_id, int $indice): VentaResponse;

    /**
     * Actualiza los adicionales asignados a una orden en un registro de producto_venta segun su indice;
     */
    public function actualizarOrdenVentaCliente(Venta $venta, $productoVenta, Producto $producto, Collection $adicionalesNuevos, int $indice): VentaResponse;

    /**
     * Cambia el estado de un item específico en el campo adicionales
     */
    public function cambiarEstadoItem(int $producto_venta_id, int $indice, string $nuevoEstado): VentaResponse;
}
