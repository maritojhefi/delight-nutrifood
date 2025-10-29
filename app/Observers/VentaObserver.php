<?php

namespace App\Observers;

use App\Models\Venta;
use App\Events\CocinaPedidoEvent;

class VentaObserver
{
    /**
     * Handle the Venta "created" event.
     *
     * @param  \App\Models\Venta  $venta
     * @return void
     */
    public function created(Venta $venta)
    {
        // event(new CocinaPedidoEvent('Venta creada', $venta->id, auth()->user()->id));
    }

    /**
     * Handle the Venta "updated" event.
     *
     * @param  \App\Models\Venta  $venta
     * @return void
     */
    public function updated(Venta $venta)
    {
        if ($venta->isDirty('cliente_id') || $venta->isDirty('tipo_entrega') || $venta->isDirty('mesa_id')) {
            if ($venta->isDirty('cliente_id')) {
                event(new CocinaPedidoEvent('El cliente ha sido cambiado', $venta->id, auth()->user()->id, 'cocina', 'info'));
                event(new CocinaPedidoEvent('El cliente ha sido cambiado', $venta->id, auth()->user()->id, 'nutribar', 'info'));
            }
            if ($venta->isDirty('tipo_entrega')) {
                switch ($venta->tipo_entrega) {
                    case 'mesa':
                        event(new CocinaPedidoEvent('El tipo de entrega ha cambiado a MESA', $venta->id, auth()->user()->id, 'cocina', 'info'));
                        event(new CocinaPedidoEvent('El tipo de entrega ha cambiado a MESA', $venta->id, auth()->user()->id, 'nutribar', 'info'));
                        break;
                    case 'delivery':
                        event(new CocinaPedidoEvent('El tipo de entrega ha cambiado a DELIVERY', $venta->id, auth()->user()->id, 'cocina', 'info'));
                        event(new CocinaPedidoEvent('El tipo de entrega ha cambiado a DELIVERY', $venta->id, auth()->user()->id, 'nutribar', 'info'));
                        break;
                    case 'recoger':
                        event(new CocinaPedidoEvent('El tipo de entrega ha cambiado a RECOGER', $venta->id, auth()->user()->id, 'cocina', 'info'));
                        event(new CocinaPedidoEvent('El tipo de entrega ha cambiado a RECOGER', $venta->id, auth()->user()->id, 'nutribar', 'info'));
                        break;
                    default:
                        event(new CocinaPedidoEvent('El tipo de entrega ha cambiado', $venta->id, auth()->user()->id, 'cocina', 'info'));
                        event(new CocinaPedidoEvent('El tipo de entrega ha cambiado', $venta->id, auth()->user()->id, 'nutribar', 'info'));
                        break;
                }
            }
            if ($venta->isDirty('mesa_id')) {
                event(new CocinaPedidoEvent('Se ha cambiado a la mesa ' . $venta->mesa->numero, $venta->id, auth()->user()->id, 'cocina', 'info'));
                event(new CocinaPedidoEvent('Se ha cambiado a la mesa ' . $venta->mesa->numero, $venta->id, auth()->user()->id, 'nutribar', 'info'));
            }
        }
    }
    /**
     * Handle the Venta "deleted" event.
     *
     * @param  \App\Models\Venta  $venta
     * @return void
     */
    public function deleted(Venta $venta)
    {
        // event(new CocinaPedidoEvent('Venta eliminada', $venta->id, auth()->user()->id));
    }

    /**
     * Handle the Venta "restored" event.
     *
     * @param  \App\Models\Venta  $venta
     * @return void
     */
    public function restored(Venta $venta)
    {
        //
    }

    /**
     * Handle the Venta "force deleted" event.
     *
     * @param  \App\Models\Venta  $venta
     * @return void
     */
    public function forceDeleted(Venta $venta)
    {
        //
    }
}
