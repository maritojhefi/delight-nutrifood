<?php

namespace App\Services\Ventas;

use App\Models\User;
use App\Models\Producto;
use App\Models\Convenio;
use App\Services\Ventas\Contracts\ConvenioServiceInterface;
use Carbon\Carbon;

class ConvenioService implements ConvenioServiceInterface
{
    public function obtenerConvenioActivo(User $usuario): ?Convenio
    {
        return $usuario->convenios()
            ->where('fecha_limite', '>=', Carbon::today())
            ->first();
    }

    public function tieneDescuentoConvenio(Producto $producto, User $usuario): bool
    {
        $convenio = $this->obtenerConvenioActivo($usuario);

        if (!$convenio) {
            return false;
        }

        $productosAfectados = json_decode($convenio->productos_afectados, true) ?? [];

        return in_array((string)$producto->id, $productosAfectados);
    }

    public function calcularDescuentoConvenio(Producto $producto, User $usuario, int $cantidad = 1): float
    {
        if (!$this->tieneDescuentoConvenio($producto, $usuario)) {
            return 0.0;
        }

        $convenio = $this->obtenerConvenioActivo($usuario);
        $precioReal = $producto->precioReal();
        $descuentoUnitario = 0.0;

        if ($convenio->tipo_descuento === 'porcentaje') {
            // Para porcentaje, valor_descuento es el porcentaje a descontar
            $descuentoUnitario = ($precioReal * $convenio->valor_descuento) / 100;
        } elseif ($convenio->tipo_descuento === 'fijo') {
            // Para fijo, valor_descuento es el monto fijo a descontar
            $descuentoUnitario = $convenio->valor_descuento;
        }

        // El descuento no puede ser mayor al precio del producto
        $descuentoUnitario = min($descuentoUnitario, $precioReal);

        return $descuentoUnitario * $cantidad;
    }

    public function obtenerPrecioConDescuentoConvenio(Producto $producto, User $usuario): float
    {
        $precioReal = $producto->precioReal();
        $descuentoConvenio = $this->calcularDescuentoConvenio($producto, $usuario, 1);

        return max(0, $precioReal - $descuentoConvenio);
    }

    /**
     * Obtiene información detallada del convenio activo para un usuario
     */
    public function obtenerInfoConvenioActivo(User $usuario): ?array
    {
        $convenio = $this->obtenerConvenioActivo($usuario);

        if (!$convenio) {
            return null;
        }

        return [
            'id' => $convenio->id,
            'nombre' => $convenio->nombre_convenio,
            'tipo_descuento' => $convenio->tipo_descuento,
            'valor_descuento' => $convenio->valor_descuento,
            'fecha_limite' => $convenio->fecha_limite,
            'productos_afectados' => json_decode($convenio->productos_afectados, true) ?? []
        ];
    }
}
