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
        // $tiene_stock = app(StockService::class)->verificarStock($this->resource,1,1);
        $tiene_stock = $this->resource->contable ? $this->resource->stockTotal() > 0 : true;

        $tiene_adicionales = $this->subcategoria->adicionales->isNotEmpty();

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'imagen'=>  $this->pathAttachment(),
            'precio' => $precioFinal,
            'precio_original' => $tieneDescuento ? $precioOriginal : null,
            'tiene_stock' => $tiene_stock,
            'url_detalle' => route('delight.detalleproducto', $this->id),
            'tiene_adicionales' => $tiene_adicionales,
            'tags' => $this->tag,
        ];
    }
}