<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Novedade;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;

class NovedadesVideosComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $titulo, $contenido, $foto;
    public $search;
    public function delete(Novedade $noticia)
    {
        $noticia->delete();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'warning',
            'message' => "Se elimino la noticia"
        ]);
    }
    public function submit()
    {
        $array = $this->validate([
            'titulo' => 'required|max:35',
            'contenido' => 'required',
            'foto' => 'required|mimes:jpeg,bmp,png,gif|max:5120'
        ]);
        //dd($array);
        $filename = time() . "." . $this->foto->extension();
        //$this->imagen->move(public_path('imagenes'),$filename);
        $this->foto->storeAs('noticias', $filename, 'public_images');
        //comprimir la foto
        $img = Image::make('imagenes/noticias/' . $filename);
        $img->resize(480, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->rotate(0);
        $img->save('imagenes/noticias/' . $filename);
        $noticia=Novedade::create($array);
        $noticia->foto=$filename;
        $noticia->save();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se agrego una nueva noticia!"
        ]);
        $this->reset();
    }

    public function render()
    {
        if ($this->search) {
            $noticias = Novedade::where('titulo', 'LIKE', '%' . $this->search . '%')->orWhere('descripcion', 'LIKE', '%' . $this->search . '%')->paginate(5);
        } else {
            $noticias = Novedade::paginate(5);
        }
        return view('livewire.admin.novedades-videos-component', compact('noticias'))
            ->extends('admin.master')
            ->section('content');
    }
}
