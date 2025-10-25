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
            'cambiarEstadoItem' => 'cambiarEstadoItem',
        ];
    }

    public function cambiarEstadoItem($producto_venta_id, $indice, $nuevoEstado)
    {
        // Llamar al servicio para cambiar el estado
        $service = app(\App\Services\Ventas\Contracts\ProductoVentaServiceInterface::class);
        $response = $service->cambiarEstadoItem($producto_venta_id, $indice, $nuevoEstado);
    }
    public function verDetalleItem($id)
    {
        $detalle = DB::table('producto_venta')->where('id', $id)->first();
        $producto = \App\Models\Producto::find($detalle->producto_id);

        // Decodificar adicionales
        $adicionales = json_decode($detalle->adicionales, true);

        // Procesar cada item con su información
        $items = [];
        if ($adicionales) {
            foreach ($adicionales as $indice => $itemData) {
                // Compatibilidad con formato antiguo y nuevo
                // dd($itemData);
                $adicionalesArray = $itemData['adicionales'] ?? $itemData;
                $agregadoAt = $itemData['agregado_at'] ?? null;
                $estado = $itemData['estado'] ?? 'pendiente';

                // Extraer nombres de adicionales
                $nombresAdicionales = [];
                if (is_array($adicionalesArray)) {
                    // dd($adicionalesArray);
                    foreach ($adicionalesArray as $adic) {
                        if (is_array($adic)) {
                            // Cada $adic es como {"Omelett de verduras":"0.00"}
                            foreach ($adic as $nombre => $precio) {
                                $nombresAdicionales[] = $nombre;
                            }
                        }
                    }
                }

                $items[] = [
                    'indice' => $indice,
                    'adicionales' => $nombresAdicionales,
                    'agregado_at' => $agregadoAt,
                    'estado' => $estado
                ];
            }
        }
        // dd($items);
        $this->dispatchBrowserEvent('verDetalleItem', [
            'producto_venta_id' => $id,
            'producto_nombre' => $producto->nombre,
            'cantidad_total' => $detalle->cantidad,
            'observacion' => $detalle->observacion,
            'items' => $items
        ]);
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
    /**
     * Calcula los contadores de estados para cada item de un producto
     */
    private function calcularContadoresEstados($adicionales)
    {
        $contadores = [
            'pendiente' => 0,
            'preparacion' => 0,
            'despachado' => 0
        ];

        if ($adicionales) {
            $adicionalesArray = json_decode($adicionales, true);
            if ($adicionalesArray) {
                foreach ($adicionalesArray as $itemData) {
                    $estado = $itemData['estado'] ?? 'pendiente';
                    if (isset($contadores[$estado])) {
                        $contadores[$estado]++;
                    }
                }
            }
        }

        return $contadores;
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
            ->map(function ($venta) {
                // Agregar contadores de estados a cada producto
                $venta->productos->each(function ($producto) {
                    $producto->contadores_estados = $this->calcularContadoresEstados($producto->pivot->adicionales);
                });
                return $venta;
            })
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
