<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ReportePlanesAdminWhatsappCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reporteDiario:planesPorVencer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza un reporte de los planes que estan por vencer a los administradores';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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
                    'ultimoDia' => $ultimo,
                    'plan_id'=>$ultimaFecha->pivot->plane_id,
                    'user_id'=>$cliente->id
                ]);
            }
        }
        $coleccion=$coleccion->sortBy('cantidadRestante');
        dd($coleccion);
    }
}
