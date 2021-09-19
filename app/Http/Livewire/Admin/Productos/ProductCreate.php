<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;

use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;

class ProductCreate extends Component
{
    use WithFileUploads;
    public $nombre, $detalle, $cat;
    public $precio , $imagen, $descuento;
 
    protected $rules = [
        'nombre' => 'required|min:6',
        'detalle' => 'required|min:15',
        'cat' => 'required',
        'precio' => 'required|integer',
        
    ];
 
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function submit()
    {
        $this->validate();
 
        // Execution doesn't reach here if validation fails.
 
       
        if($this->imagen){
            $this->validate([
                'imagen'=>'required|mimes:jpeg,bmp,png,gif|max:10240'
            ]);
            $filename= time().".". $this->imagen->extension();
            //$this->imagen->move(public_path('imagenes'),$filename);
            $this->imagen->storeAs('productos',$filename, 'public_images');
              //comprimir la foto
            $img = Image::make('imagenes/productos/'.$filename);
            $img->resize(320, null, function ($constraint) {
             $constraint->aspectRatio();
            });
             $img->rotate(0);
            $img->save('imagenes/productos/'.$filename);

            Producto::create([
                'nombre' => $this->nombre,
                'detalle' => $this->detalle,
                'subcategoria_id' => $this->cat,
                'precio' => $this->precio,
                'descuento' => $this->descuento,
                'imagen' => $filename,
                
            ]);
        }
        else
        {
            Producto::create([
                'nombre' => $this->nombre,
                'detalle' => $this->detalle,
                'subcategoria_id' => $this->cat,
                'precio' => $this->precio,
                'descuento' => $this->descuento,
                
                
            ]); 
        }
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Producto: ".$this->nombre." creado satisfactoriamente!!"
        ]);
        $this->reset();
    }
    public function render()
    {
        $subcategorias=Subcategoria::all();
        return view('livewire.admin.productos.product-create',compact('subcategorias'));
    }
}
