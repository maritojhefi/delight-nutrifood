<?php

namespace App\Http\Resources\Producto;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoListado extends JsonResource
{
    public function toArray($request)
    {
        $precioOriginal = $this->precio;
        $precioFinal = $this->precioReal();
        $tieneDescuento = $precioFinal < $precioOriginal;
        $tiene_stock = !($this->unfilteredSucursale->isNotEmpty() && $this->stock_actual == 0);
        $tiene_adicionales = $this->subcategoria->adicionales->isNotEmpty();

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'imagen'=>  '/' . $this->pathAttachment(),
            'precio' => $precioFinal,
            'precio_original' => $tieneDescuento ? $precioOriginal : null,
            'tiene_stock' => $tiene_stock,
            'url_detalle' => route('delight.detalleproducto', $this->id),
            'tiene_adicionales' => $tiene_adicionales,
            'tags' => $this->tag,
        ];
    }
}