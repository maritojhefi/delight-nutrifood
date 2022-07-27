<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\GaleriaFotos;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class TiendaComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $foto, $titulo, $descripcion;

    protected $rules = [
        'titulo' => 'required|min:6|max:30',
        'descripcion' => 'required|min:10|max:60',
        'foto'=>'required|mimes:jpeg,bmp,png,gif|max:5120'
        
    ];
    public function submit()
    {
        $this->validate();
        $filename= time().".". $this->foto->extension();
            //$this->imagen->move(public_path('imagenes'),$filename);
            $this->foto->storeAs('galeria',$filename, 'public_images');
              //comprimir la foto
            $img = Image::make('imagenes/galeria/'.$filename);
            $img->resize(720, null, function ($constraint) {
             $constraint->aspectRatio();
            });
             $img->rotate(0);
            $img->save('imagenes/galeria/'.$filename);

            GaleriaFotos::create([
                'titulo' => $this->titulo,
                'descripcion' => $this->descripcion,
                'foto' => $filename,
                
            ]);

            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Se agrego una nueva foto a la galeria!"
            ]);
            $this->reset();
    }
    public function delete(GaleriaFotos $foto)
    {
        try {
            Storage::disk('public_images')->delete('galeria/'.$foto->foto);
            $foto->delete();
            
            
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Se elimino esta foto de la galeria!"
            ]);
    
           
            
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Ups! algo salio mal"
            ]);
        }
      
    }
    public function render()
    {
        $fotos=GaleriaFotos::paginate(3);
        return view('livewire.admin.tienda-component',compact('fotos'))
        ->extends('admin.master')
        ->section('content');
    }
}
