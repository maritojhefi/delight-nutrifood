<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\User;
use App\Models\Plane;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PlanesPorExpirar extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function eliminarPlan($userId, $planId)
    {
        try {
            // Verificar si hay planes pendientes
            $planesPendientes = DB::table('plane_user')
                ->where('user_id', $userId)
                ->where('plane_id', $planId)
                ->where('estado', Plane::ESTADOPENDIENTE)
                ->count();

            if ($planesPendientes > 0) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => 'No se puede eliminar. El usuario tiene ' . $planesPendientes . ' plan(es) pendiente(s).'
                ]);
                return;
            }

            // Eliminar todos los registros del pivot relacionados con este usuario y plan
            $eliminados = DB::table('plane_user')
                ->where('user_id', $userId)
                ->where('plane_id', $planId)
                ->delete();

            if ($eliminados > 0) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Plan eliminado exitosamente'
                ]);
            } else {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'warning',
                    'message' => 'No se encontraron registros para eliminar'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Error al eliminar el plan: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $usuarios = User::has('planes')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();

        $coleccion = collect();
        foreach ($usuarios as $cliente) {
            foreach ($cliente->planes->groupBy('nombre') as $nombre => $item) {
                if ($item->where('pivot.estado', 'finalizado')->count() != 0) {
                    $ultimaFecha = $item->sortBy('pivot.start')->last();
                    $ultimo = date_format(date_create($ultimaFecha->pivot->start), 'd-M');
                    $cantidadRestante = $item->where('pivot.start', '>', date('Y-m-d'))->where('pivot.estado', 'pendiente')->count();
                    $coleccion->push([
                        'nombre' => $cliente->name,
                        'plan' => $nombre,
                        'cantidadRestante' => $cantidadRestante,
                        'ultimoDia' => $ultimo,
                        'plan_id' => $ultimaFecha->pivot->plane_id,
                        'user_id' => $cliente->id
                    ]);
                }
            }
        }
        $coleccion = $coleccion->sortBy('cantidadRestante');

        // Convertir a paginación manual
        $page = $this->page ?: 1;
        $perPage = 10;
        $coleccionPaginada = new \Illuminate\Pagination\LengthAwarePaginator(
            $coleccion->forPage($page, $perPage),
            $coleccion->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.admin.usuarios.planes-por-expirar', ['coleccion' => $coleccionPaginada])
            ->extends('admin.master')
            ->section('content');
    }
}
