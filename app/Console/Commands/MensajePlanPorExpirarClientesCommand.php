<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Plane;
use Illuminate\Console\Command;
use App\Helpers\WhatsappAPIHelper;

class MensajePlanPorExpirarClientesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificar:planExpirado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia mensajes a los usuarios que les queda 1 o 2 dias para expirar su plan ';

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
                if ($item->where('pivot.estado', 'finalizado')->count() != 0) {
                    $ultimaFecha = $item->sortBy('pivot.start')->last();
                    $ultimo = date_format(date_create($ultimaFecha->pivot->start), 'd-M');
                    $cantidadRestante = $item->where('pivot.start', '>', date('Y-m-d'))->where('pivot.estado', 'pendiente')->count();
                    if ($cantidadRestante == 1 || $cantidadRestante == 2) {
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
        }
        foreach ($coleccion as $registro) {
            $user = User::find($registro['user_id']);
            if ($user->telf!=null && $user->telf!='') {
                $plan = Plane::find($registro['plan_id']);
                WhatsappAPIHelper::enviarTemplatePersonalizado('delight_vencimiento_plan', 'text', [$registro['nombre']], 'text', [$registro['cantidadRestante'], $plan->nombre], $user->codigo_pais . $user->telf, 'es', []);
                $this->info('Se envio mensaje de aviso de expiracion de plan a '.$user->name);
            }
        }
        $this->info('Total de usuarios encontrados a la fecha '.date('d-M').' : '.$coleccion->count());
        
    }
}
