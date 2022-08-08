<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Almuerzo;
use Illuminate\Console\Command;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use App\Models\WhatsappPlanAlmuerzo;

class ActualizarTicketsWhatsappCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actualizar:tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza la lista de tickets de atencion whatsapp';

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
        $fechaManana = Carbon::parse(Carbon::now()->addDay())->format('Y-m-d');

        $clientesConPlan = DB::table('plane_user')->select(
            'plane_user.*',
            'users.name',
            'planes.nombre',
            'planes.editable'
        )->leftjoin('users', 'users.id', 'plane_user.user_id')
            ->leftjoin('planes', 'planes.id', 'plane_user.plane_id')
            ->where('estado', 'pendiente')
            ->where('users.name', 'Mario Cotave')
            ->where('planes.editable', true)
            ->where('plane_user.detalle', null)
            ->where('users.name','Mario Cotave')//para pruebas
            ->whereDate('start', $fechaManana)->get();

        //dd($clientesConPlan->groupBy('user_id'));
        $diaPlan = WhatsappAPIHelper::saber_dia($fechaManana);

        $menuDiaActual = Almuerzo::where('dia', $diaPlan)->first();
        $clientesConPlanAgrupado = $clientesConPlan->groupBy('user_id');
        //dd($clientesConPlanAgrupado);
        if ($menuDiaActual) {
            $todosConTicket = WhatsappPlanAlmuerzo::all();
            foreach ($todosConTicket as $ticket) //eliminar ticket de los que ya no tienen plan
            {
                $encontrarSiExisteEnPlan = DB::table('plane_user')->where('user_id', $ticket->cliente_id)->where('estado', 'pendiente')->first();
                if (!$encontrarSiExisteEnPlan) {
                    $ticket->delete();
                }
            }


            foreach ($clientesConPlanAgrupado as $idUser => $cantidadAlmuerzos) //crea en tabla almuerzos actualizado con la cantidad
            {

                $estadoAtencion = WhatsappPlanAlmuerzo::where('cliente_id', $idUser)->first();
                if ($estadoAtencion) {
                    $estadoAtencion->cantidad = $cantidadAlmuerzos->count();
                    $estadoAtencion->id_plane_user = $cantidadAlmuerzos[0]->id;
                    $estadoAtencion->save();
                } else {
                    WhatsappPlanAlmuerzo::create([
                        'cliente_id' => $idUser,
                        'cantidad' => $cantidadAlmuerzos->count(),
                        'id_plane_user' => $cantidadAlmuerzos[0]->id
                    ]);
                }
            }
        }
    }
}
