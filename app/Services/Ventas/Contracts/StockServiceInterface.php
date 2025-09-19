<?php

namespace App\Services\Ventas\Contracts;

use App\Models\Producto;
use App\Services\Ventas\DTOs\VentaResponse;

interface StockServiceInterface
{
    /**
     * Actualiza el stock de un producto
     */
    public function actualizarStock(Producto $producto, string $operacion, int $cantidad, int $sucursalId): VentaResponse;

    /**
     * Verifica si hay stock suficiente para un producto
     */
    public function verificarStock(Producto $producto, int $cantidad, int $sucursalId): bool;

    /**
     * Obtiene el stock total disponible de un producto en una sucursal
     */
    public function obtenerStockTotal(Producto $producto, int $sucursalId): int;
}
