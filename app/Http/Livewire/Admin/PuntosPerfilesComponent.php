<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\PerfilPunto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PuntosPerfilesComponent extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    // Propiedades para el CRUD
    public $search = '';
    public $perfil_id;
    public $nombre;
    public $porcentaje;
    public $bono;
    public $showModal = false;
    public $isEdit = false;

    // Propiedades para el modal de usuarios
    public $showModalUsuarios = false;
    public $perfilSeleccionado = null;
    public $searchUsuariosDisponibles = '';
    public $searchUsuariosAsignados = '';
    public $usuariosDisponibles = [];
    public $usuariosAsignados = [];

    protected $listeners = ['eliminar-perfil' => 'eliminarPerfil'];

    // Reglas de validación
    protected $rules = [
        'nombre' => 'required|string|min:3|max:255',
        'porcentaje' => 'required|numeric|min:0|max:100',
        'bono' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
        'porcentaje.required' => 'El porcentaje es obligatorio.',
        'porcentaje.numeric' => 'El porcentaje debe ser un número.',
        'porcentaje.min' => 'El porcentaje no puede ser menor a 0.',
        'porcentaje.max' => 'El porcentaje no puede ser mayor a 100.',
        'bono.required' => 'El bono es obligatorio.',
        'bono.numeric' => 'El bono debe ser un número.',
        'bono.min' => 'El bono no puede ser menor a 0.',
    ];

    public function render()
    {
        $perfiles = PerfilPunto::when($this->search, function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
        })->paginate(10);

        return view('livewire.admin.puntos-perfiles-component', compact('perfiles'))->extends('admin.master')->section('content');
    }

    // Método para crear nuevo perfil
    public function crearNuevo()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    // Método para editar perfil
    public function editarPerfil($id)
    {
        $perfil = PerfilPunto::findOrFail($id);
        $this->perfil_id = $perfil->id;
        $this->nombre = $perfil->nombre;
        $this->porcentaje = $perfil->porcentaje;
        $this->bono = $perfil->bono;
        $this->isEdit = true;
        $this->showModal = true;
    }

    // Método para guardar (crear o actualizar)
    public function guardar()
    {
        $this->validate();

        if ($this->isEdit) {
            $perfil = PerfilPunto::findOrFail($this->perfil_id);
            $perfil->update([
                'nombre' => $this->nombre,
                'porcentaje' => $this->porcentaje,
                'bono' => $this->bono,
            ]);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Perfil actualizado exitosamente.',
            ]);
        } else {
            PerfilPunto::create([
                'nombre' => $this->nombre,
                'porcentaje' => $this->porcentaje,
                'bono' => $this->bono,
            ]);
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Perfil creado exitosamente.',
            ]);
        }

        $this->cerrarModal();
    }

    // Método para eliminar perfil
    public function eliminarPerfil($id)
    {
        $perfil = PerfilPunto::findOrFail($id);
        $perfil->delete();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'Perfil eliminado exitosamente.',
        ]);
    }

    // Método para cerrar modal
    public function cerrarModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // Método para resetear formulario
    private function resetForm()
    {
        $this->perfil_id = null;
        $this->nombre = '';
        $this->porcentaje = '';
        $this->bono = '';
        $this->resetErrorBag();
    }

    // Método para abrir modal de usuarios
    public function agregarUsuarios($perfilId)
    {
        $this->perfilSeleccionado = PerfilPunto::findOrFail($perfilId);
        $this->cargarUsuarios();
        $this->showModalUsuarios = true;
        $this->emit('abrirModalUsuarios');
    }

    // Método para cargar usuarios disponibles y asignados
    public function cargarUsuarios()
    {
        if (!$this->perfilSeleccionado) {
            return;
        }

        // Usuarios con role_id = 4 (disponibles)
        $usuariosDisponiblesQuery = User::where('role_id', 4)->select('id', 'name', 'email', 'telf');

        if ($this->searchUsuariosDisponibles) {
            $usuariosDisponiblesQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchUsuariosDisponibles . '%')->orWhere('email', 'like', '%' . $this->searchUsuariosDisponibles . '%');
            });
        }

        // Excluir usuarios que ya están asignados a este perfil
        $usuariosAsignadosIds = $this->perfilSeleccionado->usuarios()->pluck('users.id')->toArray();
        if (!empty($usuariosAsignadosIds)) {
            $usuariosDisponiblesQuery->whereNotIn('id', $usuariosAsignadosIds);
        }

        //excluir usuarios que ya tienen algun perfil registrado en la tabla intermedia 'perfiles_puntos_users'
        $usuariosDisponiblesQuery->whereDoesntHave('perfilesPuntos');

        $this->usuariosDisponibles = $usuariosDisponiblesQuery->get();

        // Usuarios ya asignados al perfil
        $usuariosAsignadosQuery = $this->perfilSeleccionado->usuarios();

        if ($this->searchUsuariosAsignados) {
            $usuariosAsignadosQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchUsuariosAsignados . '%')->orWhere('email', 'like', '%' . $this->searchUsuariosAsignados . '%');
            });
        }

        $this->usuariosAsignados = $usuariosAsignadosQuery->get();
    }

    // Método para limpiar y normalizar el nombre (eliminar espacios, acentos, caracteres especiales, números)
    private function limpiarNombre($nombre)
    {
        // Eliminar espacios en blanco
        $nombre = str_replace(' ', '', $nombre);
        
        // Convertir a mayúsculas
        $nombre = Str::upper($nombre);
        
        // Reemplazar acentos y caracteres especiales
        $acentos = [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U',
            'á' => 'A', 'é' => 'E', 'í' => 'I', 'ó' => 'O', 'ú' => 'U', 'ü' => 'U',
            'Ñ' => 'N', 'ñ' => 'N',
        ];
        $nombre = strtr($nombre, $acentos);
        
        // Eliminar caracteres que no sean letras (números, símbolos, etc.)
        $nombre = preg_replace('/[^A-Z]/', '', $nombre);
        
        return $nombre;
    }

    // Método para generar código único basado en el nombre del usuario
    private function generarCodigoUnico($nombreUsuario)
    {
        // Limpiar el nombre: eliminar espacios, acentos, caracteres especiales y números
        $nombreLimpio = $this->limpiarNombre($nombreUsuario);
        
        // Tomar los primeros 4 caracteres como prefijo base
        // Si el nombre tiene menos de 4 caracteres, rellenar con el primer carácter repetido
        $prefijoBase = Str::substr($nombreLimpio, 0, 4);
        if (Str::length($prefijoBase) < 4 && Str::length($nombreLimpio) > 0) {
            // Rellenar con el primer carácter hasta llegar a 4
            $primerCaracter = Str::substr($prefijoBase, 0, 1);
            while (Str::length($prefijoBase) < 4) {
                $prefijoBase .= $primerCaracter;
            }
        }
        
        // Buscar todos los códigos existentes que empiecen con cualquier variación del prefijo
        // (desde 4 letras hasta 0 letras, ya que el prefijo se acorta cuando el número crece)
        $codigosExistentes = DB::table('perfiles_puntos_users')
            ->where(function($query) use ($prefijoBase) {
                // Buscar códigos que empiecen con variaciones del prefijo (4, 3, 2, 1, 0 letras)
                for ($i = 4; $i >= 0; $i--) {
                    $prefijoVariacion = Str::substr($prefijoBase, 0, $i);
                    if ($i === 4) {
                        $query->where('codigo', 'like', $prefijoVariacion . '%');
                    } else {
                        $query->orWhere('codigo', 'like', $prefijoVariacion . '%');
                    }
                }
            })
            ->pluck('codigo')
            ->toArray();
        
        $numeroMaximo = 0;
        
        // Extraer el número más alto de los códigos existentes
        // Validar que el código pertenezca realmente a este prefijo
        foreach ($codigosExistentes as $codigoExistente) {
            // El código siempre tiene 5 caracteres: PREFIJO + NÚMERO
            // Validar que tenga exactamente 5 caracteres
            if (Str::length($codigoExistente) !== 5) {
                continue;
            }
            
            // Extraer el prefijo y el número del código existente
            // El prefijo son las letras al inicio, el número son los dígitos al final
            preg_match('/^([A-Z]*)(\d+)$/', $codigoExistente, $matches);
            
            if (!empty($matches[1]) && !empty($matches[2])) {
                $prefijoExistente = $matches[1];
                $numero = (int)$matches[2];
                
                // Validar que el prefijo existente sea una variación válida del prefijo base
                // Debe ser exactamente el prefijo base o una variación quitando del final
                $esVariacionValida = false;
                for ($i = 4; $i >= 0; $i--) {
                    $prefijoVariacion = Str::substr($prefijoBase, 0, $i);
                    if ($prefijoExistente === $prefijoVariacion) {
                        $esVariacionValida = true;
                        break;
                    }
                }
                
                // Solo considerar el número si el prefijo es una variación válida
                if ($esVariacionValida && $numero > $numeroMaximo) {
                    $numeroMaximo = $numero;
                }
            }
        }
        
        // Generar el siguiente número consecutivo
        $siguienteNumero = $numeroMaximo + 1;
        
        // Calcular cuántos dígitos tiene el número
        $digitosNumero = Str::length((string)$siguienteNumero);
        
        // Calcular cuántas letras del prefijo necesitamos
        // Total siempre es 5: letras + números = 5
        $letrasNecesarias = 5 - $digitosNumero;
        
        // Asegurar que no necesitemos más letras de las disponibles (máximo 4)
        if ($letrasNecesarias > 4) {
            $letrasNecesarias = 4;
        }
        
        // Si necesitamos más letras de las que tenemos, usar todas las disponibles
        if ($letrasNecesarias < 0) {
            $letrasNecesarias = 0;
        }
        
        // Tomar solo las letras necesarias del prefijo base (desde el inicio)
        // Esto es lo que hace que se "quite al revés": RODR -> ROD -> RO -> R
        $prefijoFinal = Str::substr($prefijoBase, 0, $letrasNecesarias);
        
        // Construir el código final: PREFIJO (letras) + NÚMERO (siempre 5 caracteres total)
        $codigo = $prefijoFinal . $siguienteNumero;
        
        // Asegurar que el código tenga exactamente 5 caracteres
        $codigo = Str::substr($codigo, 0, 5);
        
        return $codigo;
    }

    // Método para agregar usuario al perfil
    public function agregarUsuarioAlPerfil($userId)
    {
        $user = User::findOrFail($userId);
        $codigo = $this->generarCodigoUnico($user->name);
        $this->perfilSeleccionado->usuarios()->attach($userId, ['codigo' => $codigo]);
        $this->cargarUsuarios();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Usuario {$user->name} agregado al perfil exitosamente. Código: {$codigo}",
        ]);
    }

    // Método para quitar usuario del perfil
    public function quitarUsuarioDelPerfil($userId)
    {
        $user = User::findOrFail($userId);
        $this->perfilSeleccionado->usuarios()->detach($userId);

        $this->cargarUsuarios();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Usuario {$user->name} removido del perfil exitosamente.",
        ]);
    }

    // Método para cerrar modal de usuarios
    public function cerrarModalUsuarios()
    {
        $this->showModalUsuarios = false;
        $this->perfilSeleccionado = null;
        $this->searchUsuariosDisponibles = '';
        $this->searchUsuariosAsignados = '';
        $this->usuariosDisponibles = [];
        $this->usuariosAsignados = [];
    }

    // Métodos para actualizar búsquedas
    public function updatedSearchUsuariosDisponibles()
    {
        $this->cargarUsuarios();
    }

    public function updatedSearchUsuariosAsignados()
    {
        $this->cargarUsuarios();
    }
}
