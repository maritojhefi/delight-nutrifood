<?php

namespace App\Services\Ventas\Contracts;

use App\Models\User;
use App\Models\Producto;
use App\Models\Convenio;

interface ConvenioServiceInterface
{
    /**
     * Obtiene el convenio activo de un usuario
     */
    public function obtenerConvenioActivo(User $usuario): ?Convenio;

    /**
     * Verifica si un producto tiene descuento por convenio
     */
    public function tieneDescuentoConvenio(Producto $producto, User $usuario): bool;

    /**
     * Calcula el descuento de convenio para un producto
     */
    public function calcularDescuentoConvenio(Producto $producto, User $usuario, int $cantidad = 1): float;

    /**
     * Obtiene el precio con descuento de convenio aplicado
     */
    public function obtenerPrecioConDescuentoConvenio(Producto $producto, User $usuario): float;
}
