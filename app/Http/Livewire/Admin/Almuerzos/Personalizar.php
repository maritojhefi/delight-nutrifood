<?php

namespace App\Http\Livewire\Admin\Almuerzos;

use Livewire\Component;
use App\Models\Almuerzo;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Personalizar extends Component
{
    use WithFileUploads;
    public $sopa, $ensalada, $imagen, $ejecutivo, $dieta, $vegetariano, $carbohidrato_1, $carbohidrato_2, $carbohidrato_3, $jugo;
    public $seleccionado;
    protected $rules = [
        'sopa' => 'required',
        'ensalada'  => 'required|min:5',
        'ejecutivo' => 'required',
        'dieta' => 'required',
        'vegetariano' => 'required',
        'carbohidrato_1' => 'required',
        'carbohidrato_2' => 'required',
        'carbohidrato_3' => 'required',
        'jugo' => 'required|min:4'

    ];
    public function cambiarAFoto()
    {
        $this->reset('seleccionado');
    }
    public function guardarFoto()
    {
        $this->validate([
            'imagen' => 'required|mimes:jpeg,bmp,png,jpg|max:10240'
        ]);
        $nombreFotoAntigua=Almuerzo::find(1);
        Storage::disk('public_images')->delete('almuerzo/'.$nombreFotoAntigua->foto);
        $filename = time() . "." . $this->imagen->extension();
        //$this->imagen->move(public_path('imagenes'),$filename);
        $this->imagen->storeAs('almuerzo', $filename, 'public_images');
        //comprimir la foto
        $img = Image::make('imagenes/almuerzo/' . $filename);
        $img->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->rotate(0);
        $img->save('imagenes/almuerzo/' . $filename);
        DB::table('almuerzos')->update(['foto' => $filename]);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se guardo la foto del menu semanal!"
        ]);
        $this->reset('imagen');
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function editar(Almuerzo $almuerzo)
    {
        $this->seleccionado = $almuerzo;
        $this->sopa = $almuerzo->sopa;
        $this->ensalada = $almuerzo->ensalada;
        $this->ejecutivo = $almuerzo->ejecutivo;
        $this->dieta = $almuerzo->dieta;
        $this->vegetariano = $almuerzo->vegetariano;
        $this->carbohidrato_1 = $almuerzo->carbohidrato_1;
        $this->carbohidrato_2 = $almuerzo->carbohidrato_2;
        $this->carbohidrato_3 = $almuerzo->carbohidrato_3;
        $this->jugo = $almuerzo->jugo;

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Se selecciono dia " . $almuerzo->dia
        ]);
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function actualizar()
    {
        $this->validate();
        $almuerzo = Almuerzo::find($this->seleccionado->id);

        $almuerzo->update([
            'sopa' => $this->sopa,
            'ensalada' => $this->ensalada,
            'ejecutivo' => $this->ejecutivo,
            'dieta' => $this->dieta,
            'vegetariano' => $this->vegetariano,
            'carbohidrato_1' => $this->carbohidrato_1,
            'carbohidrato_2' => $this->carbohidrato_2,
            'carbohidrato_3' => $this->carbohidrato_3,
            'jugo' => $this->jugo,
        ]);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "El dia " . $almuerzo->dia . " fue actualizado!"
        ]);
    }
    public function render()
    {
        
        $almuerzos = Almuerzo::all();
        return view('livewire.admin.almuerzos.personalizar', compact('almuerzos'))
            ->extends('admin.master')
            ->section('content');
    }
}
