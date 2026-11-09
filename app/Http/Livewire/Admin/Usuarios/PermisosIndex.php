<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Models\PermisoRol;
use App\Helpers\PermisoHelper;
use Illuminate\Support\Facades\DB;

class PermisosIndex extends Component
{
    public $roleId;
    // Por módulo: 'ninguno' | 'algunos' | 'todo'
    public $modo = [];
    // Por módulo: rutas marcadas cuando el modo es 'algunos'
    public $seleccion = [];
    // Permisos guardados del rol, normalizados, para detectar cambios sin guardar
    public $original = [];

    public function mount()
    {
        abort_unless(PermisoHelper::esAdmin(), 403);
        $primero = Role::whereNotIn('id', [Role::ADMIN, Role::CLIENTE])->orderBy('id')->first();
        if ($primero) {
            $this->cargarRol($primero->id);
        }
    }

    public function seleccionarRol($roleId)
    {
        if (in_array((int) $roleId, [Role::ADMIN, Role::CLIENTE])) {
            return;
        }
        $this->cargarRol($roleId);
    }

    private function cargarRol($roleId)
    {
        $this->roleId = (int) $roleId;
        $registros = PermisoRol::where('role_id', $this->roleId)->get();

        foreach (PermisoHelper::modulos() as $clave => $modulo) {
            $delModulo = $registros->where('modulo', $clave);
            if ($delModulo->whereNull('ruta')->count()) {
                $this->modo[$clave] = 'todo';
                $this->seleccion[$clave] = [];
            } elseif ($delModulo->count()) {
                $this->modo[$clave] = 'algunos';
                $this->seleccion[$clave] = $delModulo->pluck('ruta')->values()->toArray();
            } else {
                $this->modo[$clave] = 'ninguno';
                $this->seleccion[$clave] = [];
            }
        }
        $this->original = $this->normalizar();
    }

    // Al pasar de "Todo" a "Solo algunas" se parte de todas marcadas, para ir quitando
    public function updatedModo($valor, $clave)
    {
        if ($valor === 'algunos' && empty($this->seleccion[$clave])) {
            $anterior = in_array($clave . '|*', $this->original) ? 'todo' : null;
            $this->seleccion[$clave] = $anterior ? array_values(array_unique(PermisoHelper::modulos()[$clave]['items'])) : [];
        }
    }

    public function marcarTodas($clave, $marcar = true)
    {
        $this->seleccion[$clave] = $marcar ? array_values(array_unique(PermisoHelper::modulos()[$clave]['items'])) : [];
    }

    // Lista ordenada "modulo|*" o "modulo|ruta" con lo que se guardaría
    private function normalizar(): array
    {
        $resultado = [];
        foreach (PermisoHelper::modulos() as $clave => $modulo) {
            $modo = $this->modo[$clave] ?? 'ninguno';
            if ($modo === 'todo') {
                $resultado[] = $clave . '|*';
            } elseif ($modo === 'algunos') {
                foreach (array_unique($modulo['items']) as $ruta) {
                    if (in_array($ruta, $this->seleccion[$clave] ?? [])) {
                        $resultado[] = $clave . '|' . $ruta;
                    }
                }
            }
        }
        sort($resultado);
        return $resultado;
    }

    public function descartar()
    {
        $this->cargarRol($this->roleId);
    }

    public function guardar()
    {
        abort_unless(PermisoHelper::esAdmin(), 403);
        $rol = Role::whereNotIn('id', [Role::ADMIN, Role::CLIENTE])->find($this->roleId);
        if (!$rol) {
            return;
        }

        $registros = collect($this->normalizar())->map(function ($permiso) use ($rol) {
            [$modulo, $ruta] = explode('|', $permiso, 2);
            return ['role_id' => $rol->id, 'modulo' => $modulo, 'ruta' => $ruta === '*' ? null : $ruta];
        });

        DB::transaction(function () use ($rol, $registros) {
            PermisoRol::where('role_id', $rol->id)->delete();
            foreach ($registros as $registro) {
                PermisoRol::create($registro);
            }
        });
        PermisoHelper::limpiarCache();
        $this->cargarRol($rol->id);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => "Permisos de " . ucfirst($rol->nombre) . " guardados"
        ]);
    }

    public function render()
    {
        $modulos = PermisoHelper::modulos();
        $actual = $this->normalizar();

        // Resumen de cada rol: personas y módulos con acceso (el rol abierto muestra lo que se está editando)
        $personas = User::select('role_id', DB::raw('count(*) as total'))->groupBy('role_id')->pluck('total', 'role_id');
        $guardados = PermisoRol::select('role_id', 'modulo')->distinct()->get()->groupBy('role_id');
        $roles = Role::orderBy('id')->get()->map(function ($rol) use ($personas, $guardados, $actual, $modulos) {
            $rol->personas = $personas[$rol->id] ?? 0;
            $rol->bloqueado = in_array($rol->id, [Role::ADMIN, Role::CLIENTE]);
            if ($rol->id == Role::ADMIN) {
                $rol->modulosConAcceso = count($modulos);
            } elseif ($rol->id == Role::CLIENTE) {
                $rol->modulosConAcceso = 0;
            } elseif ($rol->id == $this->roleId) {
                $rol->modulosConAcceso = collect($actual)->map(fn($p) => explode('|', $p)[0])->unique()->count();
            } else {
                $rol->modulosConAcceso = isset($guardados[$rol->id]) ? $guardados[$rol->id]->count() : 0;
            }
            return $rol;
        });

        $rolActual = $roles->firstWhere('id', $this->roleId);
        $hayCambios = $actual !== $this->original;

        return view('livewire.admin.usuarios.permisos-index', compact('roles', 'modulos', 'rolActual', 'hayCambios'))
            ->extends('admin.master')
            ->section('content');
    }
}
