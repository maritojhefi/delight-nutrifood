<?php

namespace App\Services\Ventas\DTOs;

class VentaCalculosData
{
    public function __construct(
        public array $listaCuenta,
        public float $subtotal,
        public int $itemsCuenta,
        public int $puntos,
        public float $descuentoProductos,
        public float $subtotalConDescuento,
        public ?float $descuentoSaldo = 0,
        public ?float $descuentoManual = 0,
        public ?float $descuentoConvenio = 0,
        public ?float $totalAdicionales = 0,
    ) {}

    public function toArray(): array
    {
        return [
            'listaCuenta' => $this->listaCuenta,
            'subtotal' => $this->subtotal,
            'itemsCuenta' => $this->itemsCuenta,
            'puntos' => $this->puntos,
            'descuentoProductos' => $this->descuentoProductos,
            'subtotalConDescuento' => $this->subtotalConDescuento,
            'descuentoSaldo' => $this->descuentoSaldo,
            'descuentoManual' => $this->descuentoManual,
            'descuentoConvenio' => $this->descuentoConvenio,
            'totalAdicionales' => $this->totalAdicionales,
        ];
    }
}
