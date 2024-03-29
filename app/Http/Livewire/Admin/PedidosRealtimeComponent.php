<?php

namespace App\Http\Livewire\Admin;

use App\Models\Venta;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PedidosRealtimeComponent extends Component
{
    protected $listeners = ['echo:pedido-cocina,CocinaPedidoEvent' => 'mensaje'];

    public function mensaje($mensaje)
    {
        
        $this->dispatchBrowserEvent('alert', [
            'type' => 'warning',
            'message' => "".$mensaje['message']
        ]);
        $this->emit('notificacionCocina', 'tono');
    }
    public function cambiarEstado($id,$estadoActual)
    {
        //dd($id);
        if($estadoActual=='pendiente')
        {
            DB::table('producto_venta')->where('id',$id)->update(['estado_actual'=>'despachado']);
        }
        else
        {
            DB::table('producto_venta')->where('id',$id)->update(['estado_actual'=>'pendiente']);
        }
        
    }
    public function render()
    {
        $ventas=Venta::whereHas('productos.subcategoria.categoria', function (Builder $query) {
            $query->whereIn('nombre',['Cocina','Panaderia/Reposteria'] );
        })->get();
        return view('livewire.admin.pedidos-realtime-component',compact('ventas'));
    }
}
