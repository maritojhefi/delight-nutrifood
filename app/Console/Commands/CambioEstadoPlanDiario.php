<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CambioEstadoPlanDiario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:diario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cambia de estado a los registros de los planes que ya pasaron de fecha, cambiando el color y el atributo estado a finalizado';

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
        $registrosPasadosDeFechaActual = DB::table('plane_user')->where('estado','pendiente')->where('title','!=','feriado')->whereDate('start','<',Carbon::today())->get();
        if($registrosPasadosDeFechaActual)
        {
          foreach($registrosPasadosDeFechaActual as $registros)
          {
            DB::table('plane_user')->where('id',$registros->id)->update(['color'=>'#F7843A','estado'=>'finalizado']);
            
          }
          $this->info('Se realizo la actualizacion de planes con exito! -- '.date('d-m-Y h:i:s'));
        }
        else
        {
            $this->info('No se encontro ningun registro para actualizar, todo se encuentra al dia!  -- '.date('d-m-Y h:i:s'));
        }
    }
}
