<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Novedade;
use App\Helpers\GlobalHelper;
use App\Helpers\ProcesarImagen;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NovedadesVideosComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $titulo, $contenido, $foto;
    public $search;
    public function delete(Novedade $noticia)
    {
        
        
        try {
            Storage::disk('public_path')->delete($noticia->foto);
        } catch (\Throwable $th) {
            //throw $th;
        }
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
        
        try {
            // Usar el helper ProcesarImagen para procesar y guardar la imagen
            $procesarImagen = ProcesarImagen::crear($this->foto)
                ->carpeta(Novedade::RUTA_FOTO) // Carpeta donde se guardará
                ->dimensiones(480, null) // Redimensionar a máximo 480px de ancho
                ->formato($this->foto->getClientOriginalExtension()); // Mantener formato original
            
            // Guardar la imagen procesada (automáticamente usa el disco correcto según el ambiente)
            $filename = $procesarImagen->guardar();
            
            // Log de la ubicación donde se guardó la imagen
            $disco = GlobalHelper::discoArchivos();
            if ($disco === 's3') {
                $config = config('filesystems.disks.s3');
                $bucket = $config['bucket'] ?? '';
                $region = $config['region'] ?? 'us-east-1';
                $urlCompleta = "https://{$bucket}.s3.{$region}.amazonaws.com" . Novedade::RUTA_FOTO . $filename;
                Log::info("Imagen de noticia guardada en S3", [
                    'titulo' => $array['titulo'],
                    'nombre_archivo' => $filename,
                    'url_s3' => $urlCompleta,
                    'disco' => $disco
                ]);
            } else {
                $rutaLocal = public_path('imagenes/noticias/' . $filename);
                Log::info("Imagen de noticia guardada en local", [
                    'titulo' => $array['titulo'],
                    'nombre_archivo' => $filename,
                    'ruta_local' => $rutaLocal,
                    'disco' => $disco
                ]);
            }
            
            $noticia = Novedade::create($array);
            $noticia->foto = $filename;
            $noticia->save();
            
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al procesar la imagen: ' . $e->getMessage()
            ]);
            return;
        }
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
