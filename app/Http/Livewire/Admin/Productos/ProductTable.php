<?php

namespace App\Http\Livewire\Admin\Productos;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Subcategoria;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProductTable extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $buscar;
    public $productoEdit, $nombre, $precio, $puntos, $medicion, $detalle, $descuento, $imagen, $subcategoria_id, $subcategorias;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['buscar'];

    public function updatingBuscar()
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
    public function actualizarProducto()
    {

        $this->validate([
            'nombre' => 'required|min:8',
            'precio' => 'required|numeric',
            'medicion' => 'required',

            'subcategoria_id' => 'required|integer'
        ]);
        if ($this->descuento) {
            $this->validate([
                'descuento' => 'numeric|lt:precio',

            ]);
        }
        if($this->imagen)
        {
            if ($this->productoEdit->imagen != null || $this->productoEdit->imagen != "") {
                $this->validate([
                    'imagen' => 'mimes:jpg,jpeg,png|max:5120',
    
                ]);
    
                $filename = $this->productoEdit->imagen;
                
               
            } else {
                $filename = time() . "." . $this->imagen->extension();
            }
            $this->imagen->storeAs('productos', $filename, 'public_images');
            //comprimir la foto
            $img = Image::make('imagenes/productos/' . $filename);
            $img->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->rotate(0);
            $img->save('imagenes/productos/' . $filename);
            $this->productoEdit->imagen = $filename;
        }
        

        //$this->imagen->move(public_path('imagenes'),$filename);
        
        $this->productoEdit->nombre = $this->nombre;

        $this->productoEdit->precio = $this->precio;
        $this->productoEdit->puntos = $this->puntos;
        $this->productoEdit->detalle = $this->detalle;
        $this->productoEdit->subcategoria_id = $this->subcategoria_id;
        if ($this->descuento != "") {
            $this->productoEdit->descuento = $this->descuento;
        } else {
            $this->productoEdit->descuento = null;
        }
        $this->productoEdit->save();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "El producto " . $this->productoEdit->nombre . " se actualizo!"
        ]);
        $this->reset('imagen');
    }
    public function render()
    {
        $productos = Producto::where('nombre', 'LIKE', '%' . $this->buscar . '%')->orWhere('estado', $this->buscar)->orderBy('created_at', 'desc')->paginate(5);
        return view('livewire.admin.productos.product-table', compact('productos'));
    }

    public function cambiarestado(Producto $producto)
    {

        if ($producto->estado == 'activo') {
            $producto->estado = 'inactivo';
            $producto->save();
        } else {
            $producto->estado = 'activo';
            $producto->save();
        }
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "El producto " . $producto->nombre . " cambio de estado!"
        ]);
    }
    public function cambiarcontable(Producto $producto)
    {

        if ($producto->contable == true) {
            $producto->contable = false;
            $producto->save();
        } else {
            $producto->contable = true;
            $producto->save();
        }
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se guardo el cambio!"
        ]);
    }
    public function eliminar(Producto $prod)
    {
        try {
            Storage::disk('public_images')->delete('productos/' . $prod->imagen);
            $prod->delete();


            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Producto: " . $prod->nombre . " eliminado"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "Ups! no puedes borrar productos que tengan stock"
            ]);
        }
    }
}
