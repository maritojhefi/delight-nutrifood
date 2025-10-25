<?php

namespace App\Http\Livewire\Admin\Productos;

use App\Models\Tag;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Sucursale;
use App\Models\Subcategoria;
use App\Helpers\GlobalHelper;
use App\Helpers\ProcesarImagen;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductTable extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $buscar;
    public $productoEdit, $nombre, $precio, $puntos, $medicion, $detalle, $descuento, $imagen, $subcategoria_id, $subcategorias;
    public $fechaStock, $cantidadStock, $productoSeleccionado, $categorias, $categoria_select, $stockDetallado;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['buscar'];

    protected $listeners = [
        'cerrar-modal-tags' => 'cerrarModalTags',
        'actualizarSeccionProducto' => 'actualizarSeccionProducto',
    ];

    public function mount()
    {
        $this->categorias = Categoria::all();
        $this->tagsProducto = collect();
        $this->tags = collect();
    }
    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function updatingCategoriaSelect()
    {
        $this->resetPage();
    }

    public function editarProducto(Producto $producto)
    {
        $this->productoEdit = $producto;
        $this->nombre = $producto->nombre;
        $this->precio = $producto->precio;
        $this->puntos = $producto->puntos;
        $this->detalle = $producto->detalle;
        $this->medicion = $producto->medicion;
        $this->descuento = $producto->descuento;
        $this->subcategoria_id = $producto->subcategoria_id;
        $this->subcategorias = Subcategoria::all();
    }
    public function guardarStock(Producto $producto)
    {
        $this->validate([
            'cantidadStock' => 'required|numeric|integer',
            'fechaStock' => 'required|date',
        ]);

        // Execution doesn't reach here if validation fails.
        DB::beginTransaction();
        $sucursal = Sucursale::find(1);
        $sucursal->productos()->attach($producto->id);

        $registro = DB::table('producto_sucursale')->where('producto_id', $producto->id)->where('sucursale_id', $sucursal->id)->get()->last();

        //dd($registro);
        DB::table('producto_sucursale')
            ->where('id', $registro->id)
            ->update(['fecha_venc' => $this->fechaStock, 'usuario_id' => auth()->user()->id, 'cantidad' => $this->cantidadStock, 'max' => $this->cantidadStock]);
        DB::table('productos')
            ->where('id', $producto->id)
            ->update(['contable' => 1]);
        DB::commit();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se agregaron ' . $this->cantidadStock . ' productos de ' . $producto->nombre,
        ]);
        $this->reset(['cantidadStock', 'fechaStock']);
        $producto = Producto::find($producto->id);
        $this->productoSeleccionado = $producto;
    }
    public function actualizarProducto()
    {
        $this->validate([
            'nombre' => 'required|min:8',
            'precio' => 'required|numeric',
            'medicion' => 'required',

            'subcategoria_id' => 'required|integer',
        ]);
        if ($this->descuento) {
            $this->validate([
                'descuento' => 'numeric|lt:precio',
            ]);
        }
        if ($this->imagen) {
            if ($this->productoEdit->imagen != null || $this->productoEdit->imagen != '') {
                $this->validate([
                    'imagen' => 'mimes:jpg,jpeg,png,gif|max:5120',
                ]);

                // Eliminar la imagen anterior del disco correcto
                $rutaArchivoAnterior = Producto::RUTA_IMAGENES . $this->productoEdit->imagen;
                $disco = GlobalHelper::discoArchivos();
                if (Storage::disk($disco)->exists($rutaArchivoAnterior)) {
                    Storage::disk($disco)->delete($rutaArchivoAnterior);
                }
            }

            try {
                // Usar el helper ProcesarImagen para procesar y guardar la imagen
                $procesarImagen = ProcesarImagen::crear($this->imagen)
                    ->carpeta(Producto::RUTA_IMAGENES) // Carpeta donde se guardará
                    ->dimensiones(480, null) // Redimensionar a máximo 480px de ancho
                    ->formato($this->imagen->getClientOriginalExtension()); // Mantener formato original

                // Guardar la imagen procesada (automáticamente usa el disco correcto según el ambiente)
                $filename = $procesarImagen->guardar();

                // Log de la ubicación donde se guardó la imagen
                $disco = GlobalHelper::discoArchivos();
                if ($disco === 's3') {
                    $config = config('filesystems.disks.s3');
                    $bucket = $config['bucket'] ?? '';
                    $region = $config['region'] ?? 'us-east-1';
                    $urlCompleta = "https://{$bucket}.s3.{$region}.amazonaws.com" . Producto::RUTA_IMAGENES . $filename;
                    Log::info("Imagen de producto actualizada en S3", [
                        'producto_id' => $this->productoEdit->id,
                        'nombre_archivo' => $filename,
                        'url_s3' => $urlCompleta,
                        'disco' => $disco
                    ]);
                } else {
                    $rutaLocal = public_path('imagenes/productos/' . $filename);
                    Log::info("Imagen de producto actualizada en local", [
                        'producto_id' => $this->productoEdit->id,
                        'nombre_archivo' => $filename,
                        'ruta_local' => $rutaLocal,
                        'disco' => $disco
                    ]);
                }

                $this->productoEdit->imagen = $filename;
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Error al procesar la imagen: ' . $e->getMessage()
                ]);
                return;
            }
        }

        //$this->imagen->move(public_path('imagenes'),$filename);

        $this->productoEdit->nombre = $this->nombre;

        $this->productoEdit->precio = $this->precio;
        $this->productoEdit->puntos = $this->puntos;
        $this->productoEdit->detalle = $this->detalle;
        $this->productoEdit->subcategoria_id = $this->subcategoria_id;
        if ($this->descuento != '') {
            $this->productoEdit->descuento = $this->descuento;
        } else {
            $this->productoEdit->descuento = null;
        }
        $this->productoEdit->save();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'El producto ' . $this->productoEdit->nombre . ' se actualizo!',
        ]);
        $this->reset('imagen');
    }
    public function render()
    {
        $query = Producto::limitadoPorRol()->with(['subcategoria', 'tags']);

        // Filtrar por categoría si se ha seleccionado una
        if ($this->categoria_select) {
            $query->whereHas('subcategoria', function ($subQuery) {
                $subQuery->where('categoria_id', $this->categoria_select);
            });
        }

        // Filtrar por búsqueda de texto
        if ($this->buscar) {
            $query->where(function ($searchQuery) {
                $searchQuery->where('nombre', 'LIKE', '%' . $this->buscar . '%')->orWhere('estado', $this->buscar);
            });
        }

        $productos = $query->orderBy('nombre', 'asc')->paginate(12);
        return view('livewire.admin.productos.product-table', compact('productos'));
    }
    public function verStock($idProducto)
    {
        $this->productoSeleccionado = Producto::find($idProducto);
        // Cargar el stock detallado usando la función del modelo
        $this->stockDetallado = $this->productoSeleccionado->getStockDetallado();
    }
    public function cambiarestado(Producto $producto)
    {
        if ($producto->estado == 'activo') {
            $producto->estado = 'inactivo';
            // Si se vuelve inactivo, automáticamente se oculta de la tienda
            $producto->publico_tienda = false;
            $producto->save();
        } else {
            $producto->estado = 'activo';
            $producto->save();
        }
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'El producto ' . $producto->nombre . ' cambio de estado!',
        ]);
    }
    public function cambiarcontable(Producto $producto)
    {
        if ($producto->contable == true) {
            if ($producto->sucursale->count() > 0) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'warning',
                    'message' => 'Este producto tiene stock, primero elimine el stock para poder cambiar el contable del producto',
                ]);
                return;
            }
            $producto->contable = false;
            $producto->save();
        } else {
            $producto->contable = true;
            $producto->save();
        }
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Se guardo el cambio!',
        ]);
    }

    public function cambiarPublicoTienda(Producto $producto)
    {
        // Si el producto está inactivo y se intenta hacer visible en tienda
        if ($producto->estado == 'inactivo' && !$producto->publico_tienda) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'No se puede hacer visible en tienda un producto inactivo. Active el producto primero.',
            ]);
            return;
        }

        $producto->publico_tienda = !$producto->publico_tienda;
        $producto->save();

        $mensaje = $producto->publico_tienda ? 'visible en la tienda' : 'oculto de la tienda';

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'El producto ' . $producto->nombre . ' ahora está ' . $mensaje,
        ]);
    }
    public function eliminar(Producto $prod)
    {
        try {
            Storage::disk('public_images')->delete('productos/' . $prod->imagen);
            $prod->delete();

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Producto: ' . $prod->nombre . ' eliminado',
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => 'Ups! no puedes borrar productos que tengan stock',
            ]);
        }
    }

    // Propiedades para gestión de tags
    // public $modalTags = false;
    public $productoIdTags;
    public $tagsProducto;
    public $tags;
    public $searchTags = '';

    public $productoSeleccionadoTag;

    public function editarTags($idProducto)
    {
        $this->productoIdTags = $idProducto;
        $this->productoSeleccionadoTag = Producto::find($idProducto);
        // $this->modalTags = true;
        $this->cargarTags();
        $this->emit('renderizar-icono-modal-creacion-listado');
        $this->dispatchBrowserEvent('inicializar-lucide-icons');
    }

    public function cargarTags()
    {
        // Obtener tags ya asignados al producto
        $this->tagsProducto = Tag::whereHas('productos', function ($query) {
            $query->where('producto_id', $this->productoIdTags);
        })->get();

        // Obtener tags disponibles para agregar
        $query = Tag::whereNotIn('id', $this->tagsProducto->pluck('id'));

        // Aplicar búsqueda si existe
        if (!empty(trim($this->searchTags))) {
            $query->where('nombre', 'like', '%' . trim($this->searchTags) . '%');
        }

        $this->tags = $query->get();

        // Emitir evento para inicializar iconos después de cargar los tags
        $this->dispatchBrowserEvent('inicializar-lucide-icons');
    }

    public function agregarTag($tagId)
    {
        $producto = Producto::find($this->productoIdTags);
        $tag = Tag::find($tagId);

        if ($producto && $tag) {
            $producto->tags()->attach($tagId);

            // Actualizar las colecciones locales
            $this->tagsProducto->push($tag);
            $this->tags = $this->tags
                ->reject(function ($t) use ($tagId) {
                    return $t->id == $tagId;
                })
                ->values();

            $this->emit('renderizar-icono-modal-creacion-listado');
            $this->dispatchBrowserEvent('inicializar-lucide-icons');

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Tag '{$tag->nombre}' agregado al producto",
            ]);
        }
    }

    public function quitarTag($tagId)
    {
        $producto = Producto::find($this->productoIdTags);
        $tag = Tag::find($tagId);

        if ($producto && $tag) {
            $producto->tags()->detach($tagId);

            // Actualizar las colecciones locales
            $this->tagsProducto = $this->tagsProducto
                ->reject(function ($t) use ($tagId) {
                    return $t->id == $tagId;
                })
                ->values();

            $this->tags->push($tag);

            $this->emit('renderizar-icono-modal-creacion-listado');
            $this->dispatchBrowserEvent('inicializar-lucide-icons');

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Tag '{$tag->nombre}' removido del producto",
            ]);
        }
    }

    public function buscarTags()
    {
        $this->cargarTags();
        $this->emit('renderizar-icono-modal-creacion-listado');
        $this->dispatchBrowserEvent('inicializar-lucide-icons');
    }

    public function limpiarBusqueda()
    {
        $this->searchTags = '';
        $this->cargarTags();
        $this->emit('renderizar-icono-modal-creacion-listado');
        $this->dispatchBrowserEvent('inicializar-lucide-icons');
    }

    public function cerrarModalTags()
    {
        // $this->modalTags = false;
        $this->productoIdTags = null;
        $this->searchTags = '';
        $this->productoSeleccionadoTag = null;
        $this->tagsProducto = collect();
        $this->tags = collect();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'info',
            'message' => 'Modal de tags cerrado',
        ]);

        $this->emit('renderizar-icono-modal-creacion-listado');
    }

    public function updatingSearchTags()
    {
        if (strlen($this->searchTags) >= 2 || empty($this->searchTags)) {
            $this->cargarTags();
            $this->emit('renderizar-icono-modal-creacion-listado');
        }
    }

    public function updatedSearchTags()
    {
        if (strlen($this->searchTags) >= 2 || empty($this->searchTags)) {
            $this->cargarTags();
            $this->emit('renderizar-icono-modal-creacion-listado');
        }
    }

    public function actualizarSeccionProducto($productoId, $seccion)
    {
        try {
            $producto = Producto::find($productoId);

            if (!$producto) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Producto no encontrado',
                ]);
                return;
            }

            // Si la sección está vacía, establecer como null (resetear)
            if (empty($seccion)) {
                $producto->seccion = null;
                $producto->save();

                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Sección reseteada correctamente para ' . $producto->nombre,
                ]);
                return;
            }

            // Validar que la sección sea una de las permitidas
            if (!in_array($seccion, array_keys(Producto::SECCIONES))) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Sección no válida',
                ]);
                return;
            }

            $producto->seccion = $seccion;
            $producto->save();

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Sección de despacho actualizada correctamente para ' . $producto->nombre,
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al actualizar la sección: ' . $e->getMessage(),
            ]);
        }
    }
}
