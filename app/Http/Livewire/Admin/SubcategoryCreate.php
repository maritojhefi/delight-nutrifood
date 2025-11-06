<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Helpers\GlobalHelper;
use App\Helpers\ProcesarImagen;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubcategoryCreate extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $nombre, $descripcion, $categoria;
    protected $listeners = ['listar' => 'render'];
    public $subcategoria, $nombreE, $descripcionE, $categoriaE, $foto;
    public $search;
    protected $rules = [
        'nombre' => 'required',
        'descripcion' => 'required|min:5',
        'categoria' => 'required|integer',

    ];
    public function seleccionarSubcategoria(Subcategoria $subcategoria)
    {
        $this->subcategoria = $subcategoria;
        $this->nombreE = $subcategoria->nombre;
        $this->descripcionE = $subcategoria->descripcion;
        $this->categoriaE = $subcategoria->categoria_id;
    }
    public function actualizar()
    {
        $this->validate([
            'nombreE' => 'required',
            'descripcionE' => 'required|min:5',
            'categoriaE' => 'required|integer',

        ]);
        $filename = null;
        if ($this->foto) {
            $this->validate([
                'foto' => 'required|mimes:jpeg,bmp,png,gif|max:5120'
            ]);

            try {
                // Si ya existe una foto, eliminar la anterior del disco correcto
                if ($this->subcategoria->foto) {
                    $rutaArchivoAnterior = Subcategoria::RUTA_FOTO . $this->subcategoria->foto;
                    $disco = GlobalHelper::discoArchivos();
                    if (Storage::disk($disco)->exists($rutaArchivoAnterior)) {
                        Storage::disk($disco)->delete($rutaArchivoAnterior);
                    }
                }

                // Usar el helper ProcesarImagen para procesar y guardar la imagen
                $procesarImagen = ProcesarImagen::crear($this->foto)
                    ->carpeta(Subcategoria::RUTA_FOTO) // Carpeta donde se guardará
                    ->dimensiones(480, null) // Redimensionar a máximo 480px de ancho
                    ->formato($this->foto->getClientOriginalExtension()); // Mantener formato original

                // Si ya existe una foto, usar el mismo nombre
                if ($this->subcategoria->foto) {
                    $nombreSinExtension = pathinfo($this->subcategoria->foto, PATHINFO_FILENAME);
                    $procesarImagen->nombreArchivo($nombreSinExtension);
                }

                // Guardar la imagen procesada (automáticamente usa el disco correcto según el ambiente)
                $filename = $procesarImagen->guardar();
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'Error al procesar la imagen: ' . $e->getMessage()
                ]);
                return;
            }
        }

        $this->subcategoria->nombre = $this->nombreE;
        $this->subcategoria->foto = $filename;
        $this->subcategoria->descripcion = $this->descripcionE;
        $this->subcategoria->categoria_id = $this->categoriaE;
        $this->subcategoria->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Subcategoria: " . $this->subcategoria->nombre . " actualizada!!"
        ]);
        $this->reset();
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function submit()
    {
        $this->validate();

        // Execution doesn't reach here if validation fails.

        Subcategoria::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'categoria_id' => $this->categoria,

        ]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Subcategoria: " . $this->nombre . " creada satisfactoriamente!!"
        ]);
        $this->reset();
    }

    public function eliminar(Subcategoria $subcat)
    {
        try {
            // Eliminar foto de la subcategoría si existe
            if ($subcat->foto) {
                $rutaArchivo = Subcategoria::RUTA_FOTO . $subcat->foto; // Construir ruta correcta
                $disco = GlobalHelper::discoArchivos();

                if (Storage::disk($disco)->exists($rutaArchivo)) {
                    Storage::disk($disco)->delete($rutaArchivo);
                }
            }

            // Eliminar subcategoría
            $subcat->delete();

            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "Subcategoria: " . $subcat->nombre . " eliminada"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => "No se puede eliminar este registro porque está vinculado a otros"
            ]);
        }
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        if (isset($this->search)) {
            $subcategorias = Subcategoria::where('nombre', 'LIKE', '%' . $this->search . '%')->paginate(5);
        } else {
            $subcategorias = Subcategoria::paginate(5);
        }


        $categorias = Categoria::all();
        return view('livewire.admin.subcategory-create', compact('subcategorias', 'categorias'));
    }
}
