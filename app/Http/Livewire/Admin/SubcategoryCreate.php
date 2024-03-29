<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
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
            if($this->subcategoria->foto)
            {
                $filename = $this->subcategoria->foto;
            }
            else
            {
                $filename = time() . "." . $this->foto->extension();
            }
           
            //$this->imagen->move(public_path('imagenes'),$filename);
            $this->foto->storeAs('subcategorias', $filename, 'public_images');
            //comprimir la foto
            $img = Image::make('imagenes/subcategorias/' . $filename);
            $img->resize(480, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->rotate(0);
            $img->save('imagenes/subcategorias/' . $filename);
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
            $subcat->delete();
            Storage::disk('public_images')->delete('subcategorias/'.$subcat->foto);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "Subcategoria: " . $subcat->nombre . " eliminada"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "No se puede eliminar este registro"
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
