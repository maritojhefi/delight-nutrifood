<?php

namespace App\Services\Ventas\Exceptions;

use Exception;

class VentaException extends Exception
{
    public function __construct(
        string $message = "",
        public ?string $type = 'error',
        public array $data = [],
        int $code = 0,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function ventaPagada(): self
    {
        return new self('La venta ya ha sido pagada, no se puede modificar', 'warning');
    }

    public static function stockInsuficiente(string $producto): self
    {
        return new self("No existe stock suficiente para {$producto}", 'warning');
    }

    public static function sinStock(string $producto): self
    {
        return new self("No existe stock para {$producto}", 'warning');
    }

    public static function cajaCerrada(): self
    {
        return new self('La caja se encuentra cerrada!', 'error');
    }

    public static function cajaSinAbrir(): self
    {
        return new self('Aun no se abrio la caja de hoy!', 'error');
    }

    public static function metodosPagoIncorrectos(): self
    {
        return new self('Los métodos de pago no equivalen al monto total de la venta', 'error');
    }

    public static function productoConAdicionales(string $producto): self
    {
        return new self("El producto {$producto} tiene adicionales personalizados, eliminelos primero", 'warning');
    }

    public static function pedidoSinDespachar(): self
    {
        return new self('Aún no se despachó el pedido desde cocina.', 'warning');
    }
    public static function sinStockOrden(array $stockData): self
    {
        return new self('Stock insuficiente para completar la solicitud', 'warning', $stockData);
    }
}
