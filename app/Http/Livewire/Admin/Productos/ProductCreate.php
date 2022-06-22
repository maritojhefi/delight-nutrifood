<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Sucursale;

use App\Models\Subcategoria;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ProductCreate extends Component
{
    use WithFileUploads;
    public $nombre, $detalle, $cat;
    public $precio , $imagen, $descuento, $medicion, $puntos;
    public $productoSeleccionado, $sucursales, $sucursalSeleccionada=1,$fecha,$cantidad;
    protected $rules = [
        'nombre' => 'required|min:6|unique:productos,nombre',
        'detalle' => 'required|min:15',
        'cat' => 'required|integer',
        'precio' => 'required|numeric|min:0',
        
    ];
    public function addStock()
    {
        $this->validate([
            'cantidad'=>'required|integer|numeric',
            'fecha'=>'required|date',
            'sucursalSeleccionada'=>'required|integer'
        ]);
        DB::beginTransaction();
        $sucursal=Sucursale::find($this->sucursalSeleccionada);
        $sucursal->productos()->attach($this->productoSeleccionado->id);
        
        $registro = DB::table('producto_sucursale')->where('producto_id',$this->productoSeleccionado->id)->where('sucursale_id',$this->sucursalSeleccionada)->get()->last();
      

        DB::table('producto_sucursale')
        ->where('id', $registro->id)
        ->update(['fecha_venc'=>$this->fecha,'usuario_id'=>auth()->user()->id,'cantidad'=>$this->cantidad,'max'=>$this->cantidad]); 
        DB::table('productos')->where('id',$this->productoSeleccionado->id)->update(['contable'=>1]);
        DB::commit();
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se agregaron ".$this->cantidad." productos de ".$this->productoSeleccionado->nombre
        ]);
        $this->reset(['productoSeleccionado','cantidad','fecha']);
    }
    public function seleccionarProducto(Producto $producto)
    {
        $this->productoSeleccionado=$producto;
        $this->sucursales=Sucursale::all();
    }
    public function resetProducto()
    {
        $this->reset('productoSeleccionado');
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function submit()
    {
        $this->validate();
 
        // Execution doesn't reach here if validation fails.
 
       if($this->descuento=="")
       {
           $this->descuento=null;
       }
       else
       {
        $this->validate([
            'descuento'=>'lt:precio'
        ]);
       }
       if($this->puntos=="")
       {
           $this->puntos=null;
       }
       if($this->medicion=="" || $this->medicion==null)
       {
           $this->medicion='unidad';
       }
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

           $producto= Producto::create([
                'nombre' => $this->nombre,
                'detalle' => $this->detalle,
                'subcategoria_id' => $this->cat,
                'precio' => $this->precio,
                'descuento' => $this->descuento,
                'imagen' => $filename,
                'medicion' => $this->medicion,
                'puntos' => $this->puntos,
            ]);
            $producto->codigoBarra='delight'.$producto->id;
            $producto->save();
        }
        else
        {
            $producto= Producto::create([
                'nombre' => $this->nombre,
                'detalle' => $this->detalle,
                'subcategoria_id' => $this->cat,
                'precio' => $this->precio,
                'descuento' => $this->descuento,
                'medicion' => $this->medicion,
                'puntos' => $this->puntos,
                
            ]); 
            $producto->codigoBarra='delight'.$producto->id;
            $producto->save();
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
        $productos=Producto::orderBy('created_at','DESC')->take(5)->get();
        return view('livewire.admin.productos.product-create',compact('subcategorias','productos'));
    }
}
