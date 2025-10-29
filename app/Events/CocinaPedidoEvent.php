<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CocinaPedidoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;
    public $idVenta;
    public $user;
    public $seccion;
    public $icono;
    public function __construct($message = null, $idVenta = null, $user = null, $seccion = 'cocina', $icono = 'success')
    {
        $this->message = $message;
        $this->idVenta = $idVenta;
        $this->user = $user;
        $this->seccion = $seccion;
        $this->icono = $icono;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('pedido-' . $this->seccion);
    }
}
