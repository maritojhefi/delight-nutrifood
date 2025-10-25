<?php

namespace App\Http\Livewire\Admin;

use App\Models\Venta;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PedidosRealtimeComponent extends Component
{
    public $listado;
    public $seccion;

    public function mount($listado = 'infinito', $seccion = 'cocina')
    {
        $this->listado = $listado;
        $this->seccion = $seccion;
    }

    protected function getListeners()
    {
        return [
            "echo:pedido-{$this->seccion},CocinaPedidoEvent" => 'mensaje',
        ];
    }
    public function mensaje($data)
    {

        // $this->dispatchBrowserEvent('alert', [
        //     'type' => 'warning',
        //     'message' => "" . $data['message']
        // ]);
        $this->emit('notificacionCocina', $data);
    }
    public function cambiarEstado($id, $estadoActual)
    {
        //dd($id);
        if ($estadoActual == 'pendiente') {
            DB::table('producto_venta')->where('id', $id)->update(['estado_actual' => 'despachado']);
        } else {
            DB::table('producto_venta')->where('id', $id)->update(['estado_actual' => 'pendiente']);
        }
    }
    public function render()
    {
        // Obtener ventas que tengan productos de cocina (basado en la sección del producto)
        $ventas = Venta::whereHas('productos', function (Builder $query) {
            $query->where('seccion', $this->seccion);
        })
            ->with(['productos' => function ($query) {
                // Solo cargar productos de la sección cocina
                $query->where('seccion', $this->seccion);
            }, 'cliente'])
            ->get()
            ->sortBy(function ($venta) {
                // Ordenamiento inteligente para optimizar el flujo de cocina
                $ahora = now();

                if ($venta->reservado_at) {
                    // Para reservas: calcular tiempo hasta la hora de entrega
                    // Restamos 30 minutos como tiempo estimado de preparación
                    $tiempoPreparacion = 30; // minutos
                    $horaObjetivo = \Carbon\Carbon::parse($venta->reservado_at)->subMinutes($tiempoPreparacion);

                    // Si la hora objetivo ya pasó o es muy cercana, priorizar como urgente
                    $minutosHastaObjetivo = $ahora->diffInMinutes($horaObjetivo, false);

                    if ($minutosHastaObjetivo < 0) {
                        // Ya pasó la hora objetivo, es urgente
                        return $ahora->timestamp - abs($minutosHastaObjetivo) * 60;
                    } else {
                        // Aún falta tiempo, usar la hora objetivo para ordenar
                        return $horaObjetivo->timestamp;
                    }
                } else {
                    // Para pedidos inmediatos: ordenar por antigüedad (FIFO)
                    // Usar created_at para que los más antiguos tengan menor timestamp
                    return $venta->created_at->timestamp;
                }
            })
            ->values();

        return view('livewire.admin.pedidos-realtime-component', compact('ventas'));
    }
}
