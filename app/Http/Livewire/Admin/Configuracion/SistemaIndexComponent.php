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
                $esBooleano = array_key_exists($item->atributo, Setting::ATRIBUTOS_BOOLEANOS);
                return [
                    'id' => $item->id,
                    'clave' => $item->atributo,
                    'valor' => $esBooleano ? $item->valor == '1' : $item->valor,
                    'es_imagen' => (bool) $item->es_imagen,
                    'es_booleano' => $esBooleano,
                    'descripcion' => Setting::ATRIBUTOS_BOOLEANOS[$item->atributo] ?? Setting::ATRIBUTOS_DESCRIPCION[$item->atributo] ?? null,
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

            if ($setting['clave'] === Setting::DESCUENTO_MANUAL_MAXIMO) {
                $valor = str_replace(',', '.', trim((string) $setting['valor']));
                if (!is_numeric($valor) || $valor < 0 || $valor > 100) {
                    $this->emit('notify', [
                        'type' => 'error',
                        'message' => 'El descuento manual máximo debe ser un número entre 0 y 100',
                    ]);
                    return;
                }
                $setting['valor'] = $this->configuraciones[$index]['valor'] = (string) (float) $valor;
            }

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

    public function saveBooleanSetting($index)
    {
        $setting = $this->configuraciones[$index];
        if (empty($setting['es_booleano'])) {
            return;
        }

        $activo = (bool) $setting['valor'];
        Setting::find($setting['id'])->update([
            'valor' => $activo ? '1' : '0',
        ]);

        $this->emit('notify', [
            'type' => 'success',
            'message' => ucfirst(str_replace('_', ' ', $setting['clave'])) . ($activo ? ' activado' : ' desactivado'),
        ]);
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
