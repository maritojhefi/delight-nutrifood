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
        // Si el cliente tiene varios, primero los que tienen productos: un convenio informativo
        // (sin productos) no debe tapar el descuento automático de otro
        return $usuario->convenios()
            ->where('fecha_limite', '>=', Carbon::today())
            ->orderByRaw("CASE WHEN productos_afectados IS NULL OR productos_afectados IN ('', '[]', 'null') THEN 1 ELSE 0 END")
            ->orderByDesc('convenios.id')
            ->first();
    }

    /**
     * Convenios activos del cliente para mostrarlos en el POS.
     * Los informativos (sin productos) se aplican a mano como descuento manual.
     */
    public function resumenConveniosActivos(User $usuario): array
    {
        return $usuario->convenios()
            ->where('fecha_limite', '>=', Carbon::today())
            ->orderByDesc('convenios.id')
            ->get()
            ->map(function (Convenio $convenio) {
                $productos = json_decode($convenio->productos_afectados, true) ?: [];
                return [
                    'nombre' => $convenio->nombre_convenio,
                    'tipo_descuento' => $convenio->tipo_descuento,
                    'valor_descuento' => (float) $convenio->valor_descuento,
                    'cantidad_productos' => count($productos),
                    'informativo' => count($productos) === 0,
                    'fecha_limite' => $convenio->fecha_limite,
                ];
            })
            ->values()
            ->toArray();
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
