<?php

namespace App\Http\Resources\Producto;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoDetalle extends JsonResource
{
    public function toArray($request)
    {
        $precioOriginal = $this->precio;
        $precioFinal = $this->precioReal();
        $tieneDescuento = $precioFinal < $precioOriginal;
        $tiene_stock = !($this->unfilteredSucursale->isNotEmpty() && $this->stock_actual == 0);
        $adicionales = $this->subcategoria->adicionalesGrupo();

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'imagen'=>  $this->pathAttachment(),
            'precio' => $precioFinal,
            'precio_original' => $tieneDescuento ? $precioOriginal : null,
            'tiene_stock' => $tiene_stock,
            'url_detalle' => route('delight.detalleproducto', $this->id),
            'adicionales' => $adicionales->map(function ($adicional) {
                return [
                    'id' => $adicional->id,
                    'nombre' => $adicional->nombre,
                    'precio' => $adicional->precio,
                    'cantidad' => $adicional->cantidad,
                    'contable' => $adicional->contable,
                    'grupo' => $adicional->nombre_grupo ? [
                        'id' => $adicional->grupo_id,
                        'nombre' => $adicional->nombre_grupo,
                        'es_obligatorio' => (bool) $adicional->es_obligatorio,
                        'maximo_seleccionable' => $adicional->maximo_seleccionable,
                    ] : null,
                ];
            }),
        ];
    }
}
