<?php

namespace App\Http\Livewire\Admin\Productos;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ProductosPorExpirar extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';

    protected $listeners = [
        'eliminarStock' => 'eliminarStock',
        'cambiarFechaExpiracion' => 'cambiarFechaExpiracion',
        'agregarStock' => 'agregarStock',
        'vincularStock' => 'vincularStock',
        'buscarProductosParaVincular' => 'buscarProductosParaVincular',
        'eliminarVinculoStock' => 'eliminarVinculoStock',
        'cambiarEstado' => 'cambiarEstado',
        'cambiarContable' => 'cambiarContable'
    ];

    protected $queryString = [
        'search' => ['except' => '']
    ];

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
        $this->dispatchBrowserEvent('toastAlert', [
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
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => 'Se cambio la fecha de vencimiento correctamente',
        ]);
    }
    public function eliminarStock($id)
    {
        DB::table('producto_sucursale')->where('id', $id)->delete();
        $this->reset('fecha', 'cantidad');
        $this->dispatchBrowserEvent('toastAlert', [
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
                $this->dispatchBrowserEvent('toastAlert', [
                    'type' => 'error',
                    'message' => 'No se encontró el lote especificado',
                ]);
                return;
            }

            // Validar que la nueva fecha no esté vacía
            if (empty($nuevaFecha)) {
                $this->dispatchBrowserEvent('toastAlert', [
                    'type' => 'error',
                    'message' => 'La fecha no puede estar vacía',
                ]);
                return;
            }

            // Validar que la nueva fecha sea posterior a hoy
            $hoy = date('Y-m-d');
            if ($nuevaFecha <= $hoy) {
                $this->dispatchBrowserEvent('toastAlert', [
                    'type' => 'error',
                    'message' => 'La nueva fecha debe ser posterior al día de hoy',
                ]);
                return;
            }

            // Validar que la nueva fecha sea posterior a la fecha actual
            if ($nuevaFecha <= $stockActual->fecha_venc) {
                $this->dispatchBrowserEvent('toastAlert', [
                    'type' => 'error',
                    'message' => 'La nueva fecha debe ser posterior a la fecha actual de expiración (' . date('d/m/Y', strtotime($stockActual->fecha_venc)) . ')',
                ]);
                return;
            }

            // Actualizar la fecha
            DB::table('producto_sucursale')
                ->where('id', $id)
                ->update(['fecha_venc' => $nuevaFecha]);

            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'success',
                'message' => 'La fecha de expiración se cambió correctamente a ' . date('d/m/Y', strtotime($nuevaFecha)),
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'Error al cambiar la fecha de expiración: ' . $e->getMessage(),
            ]);
        }
    }
    public function cambiar()
    {
        $this->seleccionado->descuento = $this->nuevoPrecio;
        $this->seleccionado->save();
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => 'Se cambio el precio de descuento correctamente',
        ]);
        $this->reset('nuevoPrecio');
    }

    public function agregarStock($productoId, $fechaExpiracion, $cantidad)
    {
        $producto = Producto::find($productoId);

        // Validar que el producto esté activo
        if ($producto->estado !== 'activo') {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'No se puede agregar stock a un producto inactivo. Primero active el producto.',
            ]);
            return;
        }

        // Validar que el producto sea contable
        if ($producto->contable == false) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'El producto no es de tipo CONTABLE, no se puede agregar stock',
            ]);
            return;
        }

        $sucursal = Sucursale::first();
        $producto->sucursale()->attach($sucursal->id, ['fecha_venc' => Carbon::parse($fechaExpiracion)->addDay()->format('Y-m-d'), 'cantidad' => $cantidad, 'max' => $cantidad, 'usuario_id' => auth()->id()]);

        // Refrescar el producto para obtener el stock actualizado
        $producto->refresh();

        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => 'Se agregaron ' . $cantidad . ' ' . $producto->medicion . '(s) de ' . $producto->nombre . ' a esta sucursal',
        ]);

        // Emitir evento para actualizar el stock en el modal
        $this->dispatchBrowserEvent('stockActualizado', [
            'productoId' => $productoId,
            'stockTotal' => $producto->stockTotal()
        ]);
    }

    public function cambiarEstado($productoId)
    {
        $producto = Producto::find($productoId);

        if ($producto->estado === 'activo') {
            $producto->estado = 'inactivo';
            // Si se vuelve inactivo, automáticamente se oculta de la tienda
            $producto->publico_tienda = false;
            $producto->save();
            $mensaje = 'El producto ' . $producto->nombre . ' ha sido desactivado';
        } else {
            $producto->estado = 'activo';
            $producto->save();
            $mensaje = 'El producto ' . $producto->nombre . ' ha sido activado';
        }

        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => $mensaje,
        ]);

        // Emitir evento para actualizar la información en el frontend
        $this->dispatchBrowserEvent('productoActualizado', [
            'productoId' => $productoId,
            'estado' => $producto->estado === 'activo' ? 'Activo' : 'Inactivo',
            'estadoReal' => $producto->estado
        ]);
    }

    public function cambiarContable($productoId)
    {
        $producto = Producto::find($productoId);

        // Validar que el producto esté activo
        if ($producto->estado !== 'activo') {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'No se puede cambiar el contable de un producto inactivo. Primero active el producto.',
            ]);
            return;
        }

        // Validar que no tenga stock si se quiere cambiar a no contable
        if ($producto->contable == true) {
            if ($producto->sucursale->count() > 0) {
                $this->dispatchBrowserEvent('toastAlert', [
                    'type' => 'warning',
                    'message' => 'Este producto tiene stock, primero elimine el stock para poder cambiar el contable del producto',
                ]);
                return;
            }
            $producto->contable = false;
            $producto->save();
            $mensaje = 'El producto ' . $producto->nombre . ' ya no es contable';
        } else {
            $producto->contable = true;
            $producto->save();
            $mensaje = 'El producto ' . $producto->nombre . ' ahora es contable';
        }

        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => $mensaje,
        ]);

        // Emitir evento para actualizar la información en el frontend
        $this->dispatchBrowserEvent('productoActualizado', [
            'productoId' => $productoId,
            'contable' => $producto->contable ? 'Sí' : 'No',
            'contableReal' => $producto->contable
        ]);
    }
    public function eliminarVinculoStock($productoId)
    {
        $producto = Producto::find($productoId);
        $producto->producto_stock_id = null;
        $producto->save();
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => 'Se elimino el vinculo de stock del producto',
        ]);

        // Emitir evento para actualizar el vínculo en el modal
        $this->dispatchBrowserEvent('vinculoEliminado', [
            'productoId' => $productoId
        ]);
    }
    public function vincularStock($productoOrigenId, $productoDestinoId)
    {
        // Aquí irá la lógica para vincular stock de un producto a otro

        $productoOrigen = Producto::find($productoOrigenId);
        $productoDestino = Producto::find($productoDestinoId);
        if ($productoDestino->productoVinculadoStock) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => $productoDestino->nombre . ' ya esta vinculado a otro producto',
            ]);
            return;
        }
        if ($productoOrigen->productoVinculadoStock) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => $productoOrigen->nombre . ' ya esta vinculado a otro producto',
            ]);
            return;
        }
        if ($productoOrigen->productosVinculados->count() > 0) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'El producto ya esta vinculado a otros productos',
            ]);
            return;
        }
        if (!$productoOrigen->sucursale->isEmpty()) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'El producto ya tiene un stock, primero elimine el stock actual para poder vincularlo',
            ]);
            return;
        }

        if ($productoDestino->contable == false) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'El producto ' . $productoDestino->nombre . ' no es de tipo CONTABLE',
            ]);
            return;
        }

        $productoOrigen->producto_stock_id = $productoDestinoId;
        $productoOrigen->save();
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => 'Ahora el stock de ' . $productoOrigen->nombre . ' se descontara del stock de ' . $productoDestino->nombre,
        ]);

        // Emitir evento para actualizar el vínculo en el modal
        $this->dispatchBrowserEvent('vinculoActualizado', [
            'productoId' => $productoOrigenId,
            'productoVinculado' => $productoDestino->nombre
        ]);
    }

    public function buscarProductosParaVincular($busqueda)
    {
        // Buscar productos para vincular que tengan stock disponible
        $productos = Producto::where('nombre', 'LIKE', '%' . $busqueda . '%')
            ->whereHas('sucursale')
            ->take(10)
            ->get(['id', 'nombre']);

        // Emitir evento con los resultados al frontend
        $this->dispatchBrowserEvent('productosEncontrados', [
            'productos' => $productos->toArray()
        ]);
    }
    public function render()
    {
        //$productos=Producto::has('sucursale')->has('producto_sucursale')->orderBy('fecha_venc','desc')->paginate(10);

        $query = Producto::limitadoPorRol()->whereHas('sucursale')
            ->join('producto_sucursale', 'productos.id', '=', 'producto_sucursale.producto_id')
            ->select('productos.*', DB::raw('MIN(producto_sucursale.fecha_venc) as fecha_venc')) // Seleccionamos la fecha mínima
            ->groupBy('productos.id') // Agrupamos por producto para evitar repeticiones
            ->orderBy('fecha_venc', 'asc');

        if ($this->search) {
            $query->where('productos.nombre', 'LIKE', '%' . $this->search . '%');
        }

        $productos = $query->paginate(10);
        $productosSinStock = collect();
        if ($this->search) {
            $productosSinStock = Producto::limitadoPorRol()->whereDoesntHave('sucursale')->where('nombre', 'LIKE', '%' . $this->search . '%')->take(10)->get();
            // $this->reset('search');
        }

        // dd($productos);
        return view('livewire.admin.productos.productos-por-expirar', compact('productos', 'productosSinStock'))->extends('admin.master')->section('content');
    }
}
