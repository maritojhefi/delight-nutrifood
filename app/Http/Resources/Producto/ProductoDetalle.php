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
        $adicionales = $this->subcategoria->adicionales->load('grupoAdicionale');


        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'imagen'=>  $this->pathAttachment(),
            'precio' => $precioFinal,
            'precio_original' => $tieneDescuento ? $precioOriginal : null,
            'tiene_stock' => $tiene_stock,
            'url_detalle' => route('delight.detalleproducto', $this->id),
            // 'adicionales' => $this->subcategoria->adicionales,
            'adicionales' => $adicionales->map(function ($adicional) {
                return [
                    'id' => $adicional->id,
                    'nombre' => $adicional->nombre,
                    'precio' => $adicional->precio,
                    'cantidad' => $adicional->cantidad,
                    'contable' => $adicional->contable,
                    // 'pivot' => $adicional->pivot,
                    // Include grupo information
                    'grupo' => $adicional->grupoAdicionale ? [
                        'id' => $adicional->grupoAdicionale->id,
                        'nombre' => $adicional->grupoAdicionale->nombre,
                        'maximo_seleccionable' => $adicional->grupoAdicionale->maximo_seleccionable,
                        'es_obligatorio' => $adicional->grupoAdicionale->es_obligatorio,
                    ] : null,
                ];
            }),
        ];
    }
}
