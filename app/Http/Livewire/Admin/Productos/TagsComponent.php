<?php

namespace App\Http\Livewire\Admin\Productos;

use App\Models\Tag;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class TagsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['seleccionar-icono' => 'seleccionarIcono', 'eliminar-tag' => 'eliminarTag'];

    public $search;
    public $alerta = true;

    // Propiedades para el modal
    public $modalTag = false;
    public $editing = false;
    public $tagId;
    public $nombre;
    public $icono;

    protected $queryString = [
        'alerta' => ['except' => true],
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'nombre' => 'required|min:2|max:50',
        'icono' => 'required|min:2|max:50',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio',
        'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
        'nombre.max' => 'El nombre no puede tener más de 50 caracteres',
        'icono.required' => 'El icono es obligatorio',
        'icono.min' => 'El icono debe tener al menos 2 caracteres',
        'icono.max' => 'El icono no puede tener más de 50 caracteres',
    ];

    public function updatingSearch()
    {
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function crearNuevo()
    {
        $this->resetForm();
        $this->editing = false;
        $this->dispatchBrowserEvent('openModal');
    }

    public function editar($id)
    {
        $tag = Tag::findOrFail($id);
        $this->tagId = $tag->id;
        $this->nombre = $tag->nombre;
        $this->icono = $tag->icono;
        $this->editing = true;
        $this->dispatchBrowserEvent('openModal');
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function guardar()
    {
        $this->validate();

        if ($this->editing) {
            $tag = Tag::findOrFail($this->tagId);
            $tag->update([
                'nombre' => $this->nombre,
                'icono' => $this->icono,
            ]);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Tag actualizado correctamente',
            ]);
        } else {
            Tag::create([
                'nombre' => $this->nombre,
                'icono' => $this->icono,
            ]);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Tag creado correctamente',
            ]);
        }

        $this->resetForm();
        $this->dispatchBrowserEvent('closeModal');
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function eliminarTag($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Tag eliminado correctamente',
        ]);
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function resetForm()
    {
        $this->tagId = null;
        $this->nombre = '';
        $this->icono = '';
        $this->editing = false;
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function cerrarAlerta()
    {
        $this->alerta = false;
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function cerrarModal()
    {
        $this->dispatchBrowserEvent('closeModal');
    }

    public function render()
    {
        $tags = Tag::where('nombre', 'like', '%' . $this->search . '%')->paginate(12);

        return view('livewire.admin.productos.tags-component', compact('tags'))
            ->extends('admin.master')
            ->section('content');
    }

    public function seleccionarIcono($nombreIcono)
    {
        $this->icono = $nombreIcono;
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    // Propiedades para el modal de productos
    public $modalProductos = false;
    public $tagProductosId;
    public $searchProductos = '';
    public $productosDisponibles;
    public $productosDelTag;

    public $tagSeleccionado;

    public function editarProductos($tagId)
    {
        $this->tagSeleccionado = Tag::findOrFail($tagId);
        // Evitar abrir múltiples modales
        if ($this->modalProductos && $this->tagProductosId == $tagId) {
            return;
        }

        $this->tagProductosId = $tagId;
        $this->modalProductos = true;
        $this->cargarProductos();
        $this->dispatchBrowserEvent('openModalProductos');
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function cargarProductos()
    {
        if (!$this->tagProductosId) {
            return;
        }

        $tag = Tag::findOrFail($this->tagProductosId);

        // Obtener productos del tag con sus relaciones (optimizado)
        $this->productosDelTag = $tag->productos()
            ->select('productos.id', 'productos.nombre', 'subcategoria_id')
            ->with(['subcategoria:id,nombre'])
            ->get();

        // Obtener IDs de productos del tag para excluirlos
        $productosIds = $this->productosDelTag->pluck('id')->toArray();

        // Obtener productos disponibles (optimizado)
        $query = Producto::select('id', 'nombre', 'subcategoria_id')
            ->whereNotIn('id', $productosIds)
            ->with(['subcategoria:id,nombre']);

        // Aplicar filtro de búsqueda si existe
        if (!empty(trim($this->searchProductos))) {
            $searchTerm = trim($this->searchProductos);
            $query->where('nombre', 'like', '%' . $searchTerm . '%');
        }

        $this->productosDisponibles = $query->get();
    }

    public function agregarProducto($productoId)
    {
        $tag = Tag::findOrFail($this->tagProductosId);
        $tag->productos()->attach($productoId);

        // Actualizar solo las colecciones locales sin hacer consultas adicionales
        $productoAgregado = Producto::select('id', 'nombre', 'subcategoria_id')
            ->with(['subcategoria:id,nombre'])
            ->find($productoId);

        if ($productoAgregado) {
            $this->productosDelTag->push($productoAgregado);
            $this->productosDisponibles = $this->productosDisponibles->reject(function ($producto) use ($productoId) {
                return $producto->id == $productoId;
            })->values();
        }

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Producto agregado al tag correctamente',
        ]);
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function quitarProducto($productoId)
    {
        $tag = Tag::findOrFail($this->tagProductosId);
        $tag->productos()->detach($productoId);

        // Actualizar solo las colecciones locales sin hacer consultas adicionales
        $productoQuitado = $this->productosDelTag->firstWhere('id', $productoId);

        if ($productoQuitado) {
            $this->productosDelTag = $this->productosDelTag->reject(function ($producto) use ($productoId) {
                return $producto->id == $productoId;
            })->values();

            $this->productosDisponibles->push($productoQuitado);
        }

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Producto removido del tag correctamente',
        ]);
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function updatingSearchProductos()
    {
        // Solo recargar si hay un término de búsqueda o si se limpió
        if (strlen($this->searchProductos) >= 2 || $this->searchProductos === '') {
            $this->cargarProductos();
        }
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function updatedSearchProductos()
    {
        // Este método se ejecuta después de que se actualiza la propiedad
        if (strlen($this->searchProductos) >= 2 || $this->searchProductos === '') {
            $this->cargarProductos();
        }
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function buscarProductos()
    {
        // Método para búsqueda manual (botón o Enter)
        if (strlen($this->searchProductos) >= 2 || $this->searchProductos === '') {
            $this->cargarProductos();
        }
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function limpiarBusqueda()
    {
        $this->searchProductos = '';
        $this->cargarProductos();
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function cerrarModalProductos()
    {
        $this->modalProductos = false;
        $this->tagProductosId = null;
        $this->searchProductos = '';
        $this->productosDisponibles = collect();
        $this->productosDelTag = collect();
        $this->tagSeleccionado = null;

        // Emitir evento para cerrar el modal desde JavaScript
        $this->dispatchBrowserEvent('closeModalProductos');
        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function mount()
    {
        $this->productosDisponibles = collect();
        $this->productosDelTag = collect();
    }
}
