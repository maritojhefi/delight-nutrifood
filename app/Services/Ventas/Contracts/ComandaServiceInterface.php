<?php

namespace App\Services\Ventas\Contracts;

use App\Models\Venta;
use App\Services\Ventas\DTOs\VentaResponse;

interface ComandaServiceInterface
{
    /**
     * Obtiene los items de una venta agrupados por área de despacho
     * diferenciando entre items con y sin nro_ticket
     */
    public function obtenerItemsAgrupadosPorArea(Venta $venta): VentaResponse;

    /**
     * Imprime una comanda agrupando todos los items sin ticket de una sección
     */
    public function imprimirComanda(Venta $venta, string $codigoArea): VentaResponse;

    /**
     * Reimprime una comanda agrupando todos los items que comparten el mismo nro_ticket
     */
    public function reimprimirComanda(Venta $venta, string $nroTicket, string $codigoArea): VentaResponse;
}

