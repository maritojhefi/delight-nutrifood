<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Convenio;
use App\Models\Producto;
use Livewire\WithPagination;

class ConveniosIndexComponent extends Component
{
    use WithPagination;

    public $nombre_convenio,
        $tipo_descuento,
        $valor_descuento,
        $productos_afectados = [],
        $fecha_limite;
    public $convenio_id; // Para edición
    public $is_editing = false; // Bandera para saber si estamos editando


    protected $listeners = [
        'eliminar-convenio' => 'eliminarConvenio'
    ];

    public function crearNuevo()
    {
        $this->resetearCampos();
        $this->is_editing = false;
        $this->dispatchProductos();
    }

    public function editarConvenio($id)
    {
        $convenio = Convenio::findOrFail($id);

        $this->convenio_id = $id;
        $this->nombre_convenio = $convenio->nombre_convenio;
        $this->tipo_descuento = $convenio->tipo_descuento;
        $this->valor_descuento = $convenio->valor_descuento;
        $this->productos_afectados = json_decode($convenio->productos_afectados, true) ?? [];
        $this->fecha_limite = $convenio->fecha_limite;
        $this->is_editing = true;
        $this->dispatchProductos();
    }

    private function dispatchProductos()
    {
        $productos = Producto::select('id', 'nombre', 'precio')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => $item->nombre . ' - Bs. ' . number_format($item->precio, 2),
                ];
            });

        $this->emit('iniciar-librerias', $productos, $this->productos_afectados);
    }

    public function resetearCampos()
    {
        $this->reset(['convenio_id', 'nombre_convenio', 'tipo_descuento', 'valor_descuento', 'productos_afectados', 'fecha_limite', 'is_editing']);
    }

    public function submit()
    {
        // Validación de datos
        $this->validate(
            [
                'nombre_convenio' => 'required|string|max:255',
                'tipo_descuento' => 'required|in:porcentaje,fijo',
                'valor_descuento' => 'required|numeric|min:0',
                'productos_afectados' => 'required|array|min:1',
                'productos_afectados.*' => 'required|exists:productos,id',
                'fecha_limite' => 'nullable|date|after_or_equal:today',
            ],
            [
                'productos_afectados.required' => 'Debe seleccionar al menos un producto.',
                'fecha_limite.after_or_equal' => 'La fecha límite no puede ser anterior a hoy.',
            ],
        );
        // Preparar datos para guardar
        $data = [
            'nombre_convenio' => $this->nombre_convenio,
            'tipo_descuento' => $this->tipo_descuento,
            'valor_descuento' => $this->valor_descuento,
            'productos_afectados' => json_encode($this->productos_afectados),
            'fecha_limite' => $this->fecha_limite,
        ];

        // Guardar o actualizar
        if ($this->is_editing) {
            $convenio = Convenio::findOrFail($this->convenio_id);
            $convenio->update($data);
            session()->flash('message', 'Convenio actualizado correctamente.');
        } else {
            Convenio::create($data);
            session()->flash('message', 'Convenio creado correctamente.');
        }
        $alerta = [
            'icono' => 'success',
            'mensaje' => 'Se guardaron los datos del convenio con exito!'
        ];
        $this->emit('mostrar-notificacion', $alerta);
        // Cerrar modal y resetear
        $this->emit('cerrar-modal');
        $this->resetearCampos();
    }

    public function eliminarConvenio($convenioId)
    {
        $convenio = Convenio::findOrFail($convenioId);
        $convenio->usuarios()->detach();
        $convenio->delete();
        $alerta = [
            'icono' => 'info',
            'mensaje' => 'Se elimino el convenio correctamente'
        ];
        $this->emit('mostrar-notificacion', $alerta);
    }

    public function confirmarEliminacion($convenioId)
    {
        $convenio = Convenio::findOrFail($convenioId);
        $usuariosConPivot = $convenio->usuarios;
        $arrayNombres = [];
        foreach ($usuariosConPivot as $usuario) {
            array_push($arrayNombres, $usuario->name);
        }
        $this->emit('sweet-detalles-productos-eliminar', $arrayNombres, $convenio->id);
    }

    public function render()
    {
        $convenios = Convenio::paginate(10);
        return view('livewire.admin.convenios-index-component', compact('convenios'))->extends('admin.master')->section('content');
    }
}
