<?php

namespace App\Http\Resources\Producto;

use App\Services\Ventas\StockService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProductoDetalle extends JsonResource
{
    public function toArray($request)
    {
        $precioOriginal = $this->precio;
        $precioFinal = $this->precioReal();
        $tieneDescuento = $precioFinal < $precioOriginal;
        // sucursalID se encuentra hardcodeado
        // $tiene_stock = app(StockService::class)->verificarStock($this->resource,1,1);
        $tiene_stock = $this->resource->contable ? $this->resource->stockTotal() > 0 : true;

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
