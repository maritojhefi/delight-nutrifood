<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Plane;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CambiarColorFinalizadosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'color:finalizados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Usar solo para cambiar de color a los finalizados que tengan color de pendient';

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
        $registrosPasadosDeFechaActual = DB::table('plane_user')->where('estado',Plane::ESTADOFINALIZADO)->where('color',Plane::COLORPENDIENTE)->whereDate('start','<',Carbon::today())->get();
        if($registrosPasadosDeFechaActual)
        {
            $contador=0;
          foreach($registrosPasadosDeFechaActual as $registros)
          {
            DB::table('plane_user')->where('id',$registros->id)->update(['color'=>Plane::COLORFINALIZADO]);
            $contador++;
          }
          $this->info('Se realizo la actualizacion de '.$contador.' planes con exito! -- '.date('d-m-Y h:i:s'));
        }
        else
        {
            $this->info('No se encontro ningun registro para actualizar, todo se encuentra al dia!  -- '.date('d-m-Y h:i:s'));
        }
    }
}
