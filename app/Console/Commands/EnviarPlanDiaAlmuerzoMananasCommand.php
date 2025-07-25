<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Almuerzo;
use App\Models\SwitchPlane;
use App\Helpers\GlobalHelper;
use Illuminate\Console\Command;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use App\Models\WhatsappPlanAlmuerzo;

class EnviarPlanDiaAlmuerzoMananasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:enviarMenuManana';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia el menu solo en la manana a los clientes';

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
        $switcher=SwitchPlane::find(1);
        if($switcher->activo==true)
        {
            $fechaHoy = Carbon::parse(Carbon::now())->format('Y-m-d');

            $clientesConPlan = DB::table('plane_user')->select(
                'plane_user.*',
                'users.name',
                'users.whatsapp_plan',
                'planes.nombre',
                'planes.editable'
            )->leftjoin('users', 'users.id', 'plane_user.user_id')
                ->leftjoin('planes', 'planes.id', 'plane_user.plane_id')
                ->where('estado', 'pendiente')
                ->where('users.whatsapp_plan', true)//revisa si esta activo el envio de mensajes del cliente
                ->where('planes.editable', true)
                ->where('plane_user.detalle',null)
                //->where('users.name','Mario Cotave')//para pruebas
                ->whereDate('start', $fechaHoy)->get();
    
            //dd($clientesConPlan->groupBy('user_id'));
            $diaPlan = WhatsappAPIHelper::saber_dia($fechaHoy);
    
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
                            'id_plane_user'=>$cantidadAlmuerzos[0]->id
                        ]);
                    }
                }
            }
    
            if ($clientesConPlanAgrupado->count() > 0) //enviar mensajes a todos los que tengan plan ese dia
            {
                $usuariosParaAtender = WhatsappPlanAlmuerzo::where('paso_segundo', false)->get();
                if ($usuariosParaAtender->count() > 0) {
                    //dd($usuariosParaAtender);
                    foreach ($usuariosParaAtender as $cliente) {
                        $devolucion = WhatsappAPIHelper::enviarTemplateMultimedia(
                            'delight_planes',
                            [
                                $cliente->cliente->name,
                                $diaPlan,
                                $menuDiaActual->ejecutivo . '(ejecutivo)',
                                $menuDiaActual->dieta . '(dieta)',
                                $menuDiaActual->vegetariano . '(veggie)',
                                'Pedir Permiso'
                            ],
                            env('APP_ENV') == 'local' ? asset(GlobalHelper::getValorAtributoSetting('logo')) : asset('imagenes/almuerzo/'.$menuDiaActual->foto),
                            'image',
                            $cliente->cliente->telf,
                            'es'
                        );
                        $this->info('Se envio mensaje a '.$cliente->cliente->name);
                    }
                    
                } else {
                    $this->info('No se encontro ningun cliente para enviar mensajes');
                }
            }
            else
            {
                $this->info('no se encontro ningun plan para el dia de manana');
            }
        }
        else
        {
            $this->info('no se envio mensajes porque el menu se encuentra bloqueado');
        }
        
    }
}
