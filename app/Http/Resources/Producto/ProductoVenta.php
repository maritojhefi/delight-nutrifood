<?php

namespace App\Http\Resources\Producto;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoVenta extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $precioOriginal = $this->precio;
        $precioFinal = $this->precioReal();
        $tieneDescuento = $precioFinal < $precioOriginal;
        $tiene_stock = $this->resource->contable ? $this->resource->stockTotal() > 0 : true;

        // obtener stock disponible desde stockService

        return [
            "id"=> $this->id,
            'nombre' => $this->nombre,
            'imagen' => $this->pathAttachment(),
            'precio' => $precioFinal,
            'precio_original' => $tieneDescuento ? $precioOriginal : null,
            'tiene_stock' => $tiene_stock,
            'stock_disponible' => $this->obtenerStockTotal(),
            'url_detalle' => route('delight.detalleproducto', $this->id),
            'cantidad' => $this->pivot->cantidad,
        ];
    }
}
