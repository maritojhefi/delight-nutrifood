<?php

namespace App\Http\Livewire\Admin\Ventas;

use App\Models\Mesa;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MesasComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // o 'tailwind' si usas ese
    public $nombre_mesa;
    public $numero;
    public $url;
    public $codigo;

    public $isOpen = false; // Para controlar modal de creación/edición
    public $mesaId; // Para edición

    public $search = '';

    protected function rules()
    {
        // Reglas base comunes
        $rules = [
            'nombre_mesa' => 'required|string|min:1|max:100',
            'numero' => 'required|integer|min:1',
            'url' => 'required|url|max:500',
            'codigo' => 'required|string|max:50',
        ];

        // Si es CREACIÓN (no hay mesaId)
        if (!$this->mesaId) {
            $rules['numero'] .= '|unique:mesas,numero';
            $rules['codigo'] .= '|unique:mesas,codigo';
        }
        // Si es EDICIÓN (hay mesaId)
        else {
            $rules['numero'] .= '|unique:mesas,numero,' . $this->mesaId;
            $rules['codigo'] .= '|unique:mesas,codigo,' . $this->mesaId;
        }

        return $rules;
    }


    protected $messages = [
        'nombre_mesa.min' => 'El nombre de la mesa debe tener al menos 1 caracter.',
        'nombre_mesa.required' => 'El nombre de la mesa es obligatorio.',
        'numero.required' => 'El número de mesa es obligatorio.',
        'numero.unique' => 'Este número de mesa ya está registrado.',
        'url.required' => 'La URL es obligatoria.',
        'url.url' => 'Debe ingresar una URL válida.',
        'codigo.required' => 'El código es obligatorio.',
        'codigo.unique' => 'Este código ya está en uso.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $mesas = Mesa::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre_mesa', 'like', '%' . $this->search . '%')
                        ->orWhere('numero', 'like', '%' . $this->search . '%')
                        ->orWhere('codigo', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.admin.ventas.mesas-component', compact('mesas'))
            ->extends('admin.master')
            ->section('content');
    }
    public function create()
    {
        $this->resetInputFields();
        if ($this->isOpen == true) {
            $this->isOpen = false;
        } else {
            $this->isOpen = true;
        }
        $this->mesaId = null;
    }
    public function submit()
    {
        if (empty($this->codigo)) {
            $this->codigo = uniqid();
        }
        if (empty($this->url)) {
            $this->url = url('/') . '/mesa' . '/' . $this->codigo;
        }
        // Validar los datos
        $this->validate();
        Mesa::updateOrCreate(
            ['id' => $this->mesaId],
            [
                'nombre_mesa' => $this->nombre_mesa,
                'numero' => $this->numero,
                'url' => $this->url,
                'codigo' => $this->codigo,
            ]
        );

        $this->emit('datos-guardados');
        $this->closeModal();
        $this->resetInputFields();
    }

    // Editar una mesa existente
    public function edit($id)
    {
        $mesa = Mesa::findOrFail($id);
        $this->mesaId = $id;
        $this->nombre_mesa = $mesa->nombre_mesa;
        $this->numero = $mesa->numero;
        $this->url = $mesa->url;
        $this->codigo = $mesa->codigo;
        $this->isOpen = true;
    }

    // Eliminar una mesa
    public function delete($id)
    {
        Mesa::find($id)->delete();
        session()->flash('message', 'Mesa eliminada correctamente.');
    }

    // Cierra el modal y resetea
    public function closeModal()
    {
        $this->isOpen = false;
    }

    // Resetea los campos de entrada
    private function resetInputFields()
    {
        $this->nombre_mesa = '';
        $this->numero = '';
        $this->url = '';
        $this->codigo = '';
    }

    public function descargarQrDiseno($mesaId)
    {
        $mesa = Mesa::findOrFail($mesaId);
        $url = $mesa->url;

        // Configuración
        $qrWidth = 700;
        $offsetY = 140;
        $padding = 15;
        $cornerRadius = 20;
        $backgroundColor = 'rgb(255, 255, 255)';

        // 1. Generar QR como string binario
        $qrCode = QrCode::format('png')
            ->size($qrWidth)
            ->margin(2)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($url);

        // 2. Convertir a imagen usando un método que Intervention pueda leer
        $qrImage = Image::make(base64_encode($qrCode))
            ->resize($qrWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });

        // 3. Cargar logo (método seguro)
        $logoPath = public_path('logo2.png');
        if (!file_exists($logoPath)) {
            throw new \Exception("El archivo del logo no existe: $logoPath");
        }
        $logo = Image::make(file_get_contents($logoPath))
            ->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        // Resto del código permanece igual...
        $logoBackground = Image::canvas(
            $logo->width() + $padding * 2,
            $logo->height() + $padding * 2
        )->fill($backgroundColor);

        $logoBackground->insert($logo, 'center')
            ->rectangle(
                0,
                0,
                $logoBackground->width(),
                $logoBackground->height(),
                function ($draw) use ($cornerRadius) {
                    $draw->border(0, '#000000', $cornerRadius);
                }
            );

        $qrImage->insert($logoBackground, 'center');

        $background = Image::make(public_path('images/qr-design/QR-DELIGHT.png'));
        $x = (int) (($background->width() - $qrImage->width()) / 2);
        $y = (int) (($background->height() - $qrImage->height()) / 2) + $offsetY;
        $background->insert($qrImage, 'top-left', $x, $y);

        //introducir letras a la imagen
        $colorLetra = '#6F6F6F';
        $centro = $background->width() / 2;

        $background->text(
            'Delight',
            $centro, // Omitimos X para usar align center
            350,  // Tu posición Y personalizada
            function ($font) use ($colorLetra) {
                $font->file(public_path('fuentes/opensans-italic.ttf'));
                $font->size(100);
                $font->color($colorLetra);
                $font->align('center'); // Centrado horizontal automático
            }
        );

        $background->text(
            'Nutri-Food/Comida Nutritiva',
            $centro, // Omitimos X para usar align center
            450,  // Tu posición Y personalizada
            function ($font) use ($colorLetra) {
                $font->file(public_path('fuentes/opensans-italic.ttf'));
                $font->size(70);
                $font->color($colorLetra);
                $font->align('center'); // Centrado horizontal automático
            }
        );
        $background->text(
            '"Nutriendo tus hábitos"',
            $centro, // Omitimos X para usar align center
            550,  // Tu posición Y personalizada
            function ($font) use ($colorLetra) {
                $font->file(public_path('fuentes/opensans-italic.ttf'));
                $font->size(50);
                $font->color($colorLetra);
                $font->align('center'); // Centrado horizontal automático
            }
        );

        $background->text(
            strtoupper($mesa->nombre_mesa),
            $centro, // Omitimos X para usar align center
            750,  // Tu posición Y personalizada
            function ($font) use ($colorLetra) {
                $font->file(public_path('fuentes/opensans-italic.ttf'));
                $font->size(160);
                $font->color($colorLetra);
                $font->align('center'); // Centrado horizontal automático
            }
        );


        $background->text(
            'Escanéame para ver el menú y promociones',
            $centro, // Omitimos X para usar align center
            1800,  // Tu posición Y personalizada
            function ($font) use ($colorLetra) {
                $font->file(public_path('fuentes/opensans-italic.ttf'));
                $font->size(50);
                $font->color($colorLetra);
                $font->align('center'); // Centrado horizontal automático
            }
        );

        $background->text(
            'Calle Campero y 15 de abril',
            $centro, // Omitimos X para usar align center
            1900,  // Tu posición Y personalizada
            function ($font) use ($colorLetra) {
                $font->file(public_path('fuentes/opensans-italic.ttf'));
                $font->size(40);
                $font->color($colorLetra);
                $font->align('center'); // Centrado horizontal automático
            }
        );

        $background->text(
            'Tarija-Bolivia',
            $background->width() / 2, // Omitimos X para usar align center
            2000,  // Tu posición Y personalizada
            function ($font) use ($colorLetra) {
                $font->file(public_path('fuentes/opensans-italic.ttf'));
                $font->size(40);
                $font->color($colorLetra);
                $font->align('center'); // Centrado horizontal automático
            }
        );

        return response()->stream(
            function () use ($background) {
                echo $background->encode('png', 100);
            },
            200,
            [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="qr-diseno-' . time() . '.png"',
            ]
        );
    }



    public function descargarQr($mesaId)
    {
        $mesa = Mesa::findOrFail($mesaId);
        $url = $mesa->url;

        // Configuración
        $qrWidth = 700;
        $offsetY = 140;
        $padding = 15;
        $cornerRadius = 20;
        $backgroundColor = 'rgb(255, 255, 255)';

        // 1. Generar QR como string binario
        $qrCode = QrCode::format('png')
            ->size($qrWidth)
            ->margin(2)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($url);

        // 2. Convertir a imagen usando un método que Intervention pueda leer
        $qrImage = Image::make(base64_encode($qrCode))
            ->resize($qrWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });

        // 3. Cargar logo (método seguro)
        $logoPath = public_path('logo2.png');
        if (!file_exists($logoPath)) {
            throw new \Exception("El archivo del logo no existe: $logoPath");
        }
        $logo = Image::make(file_get_contents($logoPath))
            ->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        // Resto del código permanece igual...
        $logoBackground = Image::canvas(
            $logo->width() + $padding * 2,
            $logo->height() + $padding * 2
        )->fill($backgroundColor);

        $logoBackground->insert($logo, 'center')
            ->rectangle(
                0,
                0,
                $logoBackground->width(),
                $logoBackground->height(),
                function ($draw) use ($cornerRadius) {
                    $draw->border(0, '#000000', $cornerRadius);
                }
            );
        $qrImage->insert($logoBackground, 'center');
        return response()->stream(
            function () use ($qrImage) {
                echo $qrImage->encode('png', 100);
            },
            200,
            [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="qr-diseno-' . time() . '.png"',
            ]
        );
    }
}
