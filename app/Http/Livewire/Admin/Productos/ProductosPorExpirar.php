<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ProductosPorExpirar extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    protected $listeners = ['eliminarStock' => 'eliminarStock', 'cambiarFechaExpiracion' => 'cambiarFechaExpiracion'];
    public $search, $seleccionado, $nuevoPrecio;
    public $cantidad, $fecha;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function seleccionar(Producto $producto)
    {
        $this->seleccionado = $producto;
        $this->nuevoPrecio = $this->seleccionado->descuento;
    }
    public function actualizarCantidad($id)
    {
        //dd($id);
        DB::table('producto_sucursale')
            ->where('id', $id)
            ->update(['cantidad' => $this->cantidad]);
        $this->reset('cantidad');
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se cambio la cantidad de stock correctamente',
        ]);
    }
    public function actualizarFecha($id)
    {
        //dd($id);
        DB::table('producto_sucursale')
            ->where('id', $id)
            ->update(['fecha_venc' => $this->fecha]);
        $this->reset('fecha');
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se cambio la fecha de vencimiento correctamente',
        ]);
    }
    public function eliminarStock($id)
    {
        DB::table('producto_sucursale')->where('id', $id)->delete();
        $this->reset('fecha', 'cantidad');
        $this->dispatchBrowserEvent('alert', [
            'type' => 'warning',
            'message' => 'Se elimino el lote, stock actualizado',
        ]);
    }

    public function cambiarFechaExpiracion($id, $nuevaFecha)
    {
        try {
            $nuevaFecha = date('Y-m-d', strtotime($nuevaFecha));
            // Obtener el registro actual
            $stockActual = DB::table('producto_sucursale')->where('id', $id)->first();
            
            if (!$stockActual) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'No se encontró el lote especificado',
                ]);
                return;
            }

            // Validar que la nueva fecha no esté vacía
            if (empty($nuevaFecha)) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'La fecha no puede estar vacía',
                ]);
                return;
            }

            // Validar que la nueva fecha sea posterior a hoy
            $hoy = date('Y-m-d');
            if ($nuevaFecha <= $hoy) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'La nueva fecha debe ser posterior al día de hoy',
                ]);
                return;
            }

            // Validar que la nueva fecha sea posterior a la fecha actual
            if ($nuevaFecha <= $stockActual->fecha_venc) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'La nueva fecha debe ser posterior a la fecha actual de expiración (' . date('d/m/Y', strtotime($stockActual->fecha_venc)) . ')',
                ]);
                return;
            }

            // Actualizar la fecha
            DB::table('producto_sucursale')
                ->where('id', $id)
                ->update(['fecha_venc' => $nuevaFecha]);

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'La fecha de expiración se cambió correctamente a ' . date('d/m/Y', strtotime($nuevaFecha)),
            ]);

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al cambiar la fecha de expiración: ' . $e->getMessage(),
            ]);
        }
    }
    public function cambiar()
    {
        $this->seleccionado->descuento = $this->nuevoPrecio;
        $this->seleccionado->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se cambio el precio de descuento correctamente',
        ]);
        $this->reset('nuevoPrecio');
    }
    public function render()
    {
        //$productos=Producto::has('sucursale')->has('producto_sucursale')->orderBy('fecha_venc','desc')->paginate(10);

        $query = Producto::whereHas('sucursale')
            ->join('producto_sucursale', 'productos.id', '=', 'producto_sucursale.producto_id')
            ->select('productos.*', DB::raw('MIN(producto_sucursale.fecha_venc) as fecha_venc')) // Seleccionamos la fecha mínima
            ->groupBy('productos.id') // Agrupamos por producto para evitar repeticiones
            ->orderBy('fecha_venc', 'asc');

        if ($this->search) {
            $query->where('productos.nombre', 'LIKE', '%' . $this->search . '%');
        }

        $productos = $query->paginate(10);

        // dd($productos);
        return view('livewire.admin.productos.productos-por-expirar', compact('productos'))->extends('admin.master')->section('content');
    }
}
