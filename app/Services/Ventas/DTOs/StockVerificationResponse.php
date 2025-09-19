<?php

namespace App\Services\Ventas\DTOs;

class StockVerificationResponse
{
    public function __construct(
        public bool $success,
        public int|float $stockProducto,
        public int $cantidadSolicitada,
        public int|float $cantidadMaximaPosible,
        public array $producto,
        public array $adicionales
    ) {}

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'stockProducto' => $this->stockProducto,
            'cantidadSolicitada' => $this->cantidadSolicitada,
            'cantidadMaximaPosible' => $this->cantidadMaximaPosible,
            'producto' => $this->producto,
            'adicionales' => $this->adicionales,
            
            'idsAdicionalesAgotados' => collect($this->adicionales['agotados'])->pluck('id')->toArray(),
            'idsAdicionalesLimitados' => collect($this->adicionales['limitados'])->pluck('id')->toArray(),
            'messageAgotados' => $this->getMessageAgotados(),
            'messageLimitados' => $this->getMessageLimitados(),
            'messageProducto' => $this->getMessageProducto(),
        ];
    }

    private function getMessageAgotados(): ?string
    {
        if (empty($this->adicionales['agotados'])) {
            return null;
        }
        
        $nombres = collect($this->adicionales['agotados'])->pluck('nombre')->implode(', ');
        return "Los siguientes adicionales se encuentran agotados: {$nombres}";
    }

    private function getMessageLimitados(): ?string
    {
        if (empty($this->adicionales['limitados'])) {
            return null;
        }
        
        $detalles = collect($this->adicionales['limitados'])
            ->map(fn($item) => "{$item['nombre']} ({$item['stock']})")
            ->implode(', ');
        
        return "Stock limitado para adicionales: {$detalles}. Puedes actualizar tu orden presionando el botón de abajo.";
    }

    private function getMessageProducto(): ?string
    {
        if ($this->producto['suficiente']) {
            return null;
        }
        
        return "Stock disponible: {$this->stockProducto}, Solicitado: {$this->cantidadSolicitada}";
    }

    // Método de conveniencia para convertir a VentaResponse en caso de error
    public function toVentaResponse(): VentaResponse
    {
        if ($this->success) {
            return VentaResponse::success($this->toArray(), 'Stock suficiente');
        }
        
        return VentaResponse::error(
            'Stock insuficiente',
            [],
            $this->toArray()
        );
    }
}