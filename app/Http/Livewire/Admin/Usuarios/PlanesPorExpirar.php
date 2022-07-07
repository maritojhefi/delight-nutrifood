<?php

namespace App\Http\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;

class PlanesPorExpirar extends Component
{
    public function render()
    {
        $usuarios = User::has('planes')->get();
        $coleccion = collect();
        foreach ($usuarios as $cliente) {
            foreach ($cliente->planes->groupBy('nombre') as $nombre => $item) {

                $ultimaFecha = $item->sortBy('pivot.start')->last();
                $ultimo = date_format(date_create($ultimaFecha->pivot->start), 'd-M');
                $cantidadRestante = $item->where('pivot.start', '>', date('Y-m-d'))->where('pivot.estado', 'pendiente')->count();
                $coleccion->push([
                    'nombre' => $cliente->name,
                    'plan' => $nombre,
                    'cantidadRestante' => $cantidadRestante,
                    'ultimoDia' => $ultimo
                ]);
            }
        }
        $coleccion=$coleccion->sortBy('cantidadRestante');

        return view('livewire.admin.usuarios.planes-por-expirar', compact('coleccion'))
            ->extends('admin.master')
            ->section('content');
    }
}
