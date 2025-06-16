<?php

namespace App\Http\Livewire\Admin\Configuracion;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SistemaIndexComponent extends Component
{
    use WithFileUploads;

    public $configuraciones = [];
    public $tempImages = [];
    public $saving = false;
    public $uploadingIndex = null;
    public $validationStates = []; // Para rastrear estados de validación

    protected $rules = [
        'configuraciones.*.valor' => 'required',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->configuraciones = Setting::all()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'clave' => $item->atributo,
                    'valor' => $item->valor,
                    'es_imagen' => (bool) $item->es_imagen,
                    'valor_file' => null,
                ];
            })
            ->toArray();
    }

    public function saveTextSetting($index)
    {
        $this->saving = true;

        try {
            $setting = $this->configuraciones[$index];

            Setting::find($setting['id'])->update([
                'valor' => $setting['valor'],
            ]);

            $this->emit('notify', [
                'type' => 'success',
                'message' => 'Configuración actualizada correctamente',
            ]);

            // Resetear estado de validación si existe
            if (isset($this->validationStates[$index])) {
                unset($this->validationStates[$index]);
            }
        } finally {
            $this->saving = false;
        }
    }

    public function saveImageSetting($index)
    {
        $this->validate([
            'configuraciones.' . $index . '.valor' => 'required'
        ]);

        $setting = $this->configuraciones[$index];
        $path = $setting['valor'];

        // Verificar que la ruta termina con una extensión de archivo
        $hasFileExtension = preg_match('/\.[a-zA-Z0-9]+$/', $path);

        // Verificar existencia y que es un archivo (no directorio)
        $fullPath = public_path($path);
        $fileExists = $hasFileExtension && file_exists($fullPath) && is_file($fullPath);

        // Lista de errores
        $errors = [];

        if (!$hasFileExtension) {
            $errors[] = 'Debe especificar un archivo con extensión (ej: .png, .jpg)';
        } else if (!file_exists($fullPath)) {
            $errors[] = 'El archivo no existe en la ruta especificada';
        } else if (!is_file($fullPath)) {
            $errors[] = 'La ruta especificada no es un archivo válido';
        }

        // Guardar estado de validación
        $this->validationStates[$index] = [
            'is_valid' => $fileExists,
            'message' => $fileExists ? '' : implode(', ', $errors)
        ];

        if ($fileExists) {
            $this->saveTextSetting($index);
            $this->emit('notify', [
                'type' => 'success',
                'message' => 'Imagen actualizada correctamente',
            ]);
        } else {
            $this->emit('notify', [
                'type' => 'error',
                'message' => 'No se puede guardar la configuración',
                'errors' => $errors // Enviamos los errores como array
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.configuracion.sistema-index-component')
            ->extends('admin.master')
            ->section('content');
    }
}
