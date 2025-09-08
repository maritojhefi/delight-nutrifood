<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $precioOriginal = $this->precio;
        $precioFinal = $this->descuento > 0 ? $this->descuento : $this->precio;
        $tieneDescuento = $this->descuento > 0 && $this->descuento < $this->precio;

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'precio' => $precioFinal,
            'precio_original' => $tieneDescuento ? $precioOriginal : null,
            'tiene_descuento' => $tieneDescuento,
            'descuento_porcentaje' => $tieneDescuento ? 
                round((($precioOriginal - $precioFinal) / $precioOriginal) * 100) : null,
            'imagen' => $this->pathAttachment(),
            'adicionales' => $this->subcategoria->adicionales,
        ];
    }
}