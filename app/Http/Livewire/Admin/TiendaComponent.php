<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\GaleriaFotos;
use App\Helpers\GlobalHelper;
use App\Helpers\ProcesarImagen;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
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
        'foto' => 'required|mimes:jpeg,bmp,png,gif|max:5120'

    ];
    public function submit()
    {
        $this->validate();
        
        try {
            // Usar el helper ProcesarImagen para procesar y guardar la imagen
            $procesarImagen = ProcesarImagen::crear($this->foto)
                ->carpeta(GaleriaFotos::RUTA_FOTO) // Carpeta donde se guardará
                ->dimensiones(720, null) // Redimensionar a máximo 720px de ancho
                ->formato($this->foto->getClientOriginalExtension()); // Mantener formato original
            
            // Guardar la imagen procesada (automáticamente usa el disco correcto según el ambiente)
            $filename = $procesarImagen->guardar();
            
            // Log de la ubicación donde se guardó la imagen
            $disco = GlobalHelper::discoArchivos();
            if ($disco === 's3') {
                $config = config('filesystems.disks.s3');
                $bucket = $config['bucket'] ?? '';
                $region = $config['region'] ?? 'us-east-1';
                $urlCompleta = "https://{$bucket}.s3.{$region}.amazonaws.com" . GaleriaFotos::RUTA_FOTO . $filename;
                Log::info("Imagen de galería guardada en S3", [
                    'titulo' => $this->titulo,
                    'nombre_archivo' => $filename,
                    'url_s3' => $urlCompleta,
                    'disco' => $disco
                ]);
            } else {
                $rutaLocal = public_path('imagenes/galeria/' . $filename);
                Log::info("Imagen de galería guardada en local", [
                    'titulo' => $this->titulo,
                    'nombre_archivo' => $filename,
                    'ruta_local' => $rutaLocal,
                    'disco' => $disco
                ]);
            }

            GaleriaFotos::create([
                'titulo' => $this->titulo,
                'descripcion' => $this->descripcion,
                'foto' => $filename,
            ]);

            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => "Se agrego una nueva foto a la galeria!"
            ]);
            $this->reset();
            
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al procesar la imagen: ' . $e->getMessage()
            ]);
            return;
        }
    }
    public function delete(GaleriaFotos $foto)
    {
        try {
            // Eliminar foto de la galería si existe
            if ($foto->foto) {
                $rutaArchivo = GaleriaFotos::RUTA_FOTO . $foto->foto; // Construir ruta correcta
                $disco = GlobalHelper::discoArchivos();
                
                if (Storage::disk($disco)->exists($rutaArchivo)) {
                    Storage::disk($disco)->delete($rutaArchivo);
                }
            }
            
            // Eliminar foto de la galería
            $foto->delete();

            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "Se elimino esta foto de la galeria!"
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => "No se puede eliminar este registro porque está vinculado a otros"
            ]);
        }
    }
    public function render()
    {
        $fotos = GaleriaFotos::paginate(3);
        return view('livewire.admin.tienda-component', compact('fotos'))
            ->extends('admin.master')
            ->section('content');
    }
}
