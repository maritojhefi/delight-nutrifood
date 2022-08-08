<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Plane;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FinalizarPlanesDiarios10AMCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finalizarPlanTodos:diario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finaliza los planes diarios a las 10 am';

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
        DB::table('plane_user')->whereDate('start', Carbon::today())->where('estado', Plane::ESTADODESARROLLO)->update(['estado' => Plane::ESTADOFINALIZADO, 'color' => Plane::COLORFINALIZADO,'detalle'=>null]);

        DB::table('plane_user')->whereDate('start', Carbon::today())->where('estado', Plane::ESTADOPENDIENTE)->update(['estado' => Plane::ESTADOFINALIZADO, 'color' => Plane::COLORFINALIZADO]);

        DB::table('whatsapp_plan_almuerzos')->delete();
        $this->info('Se finalizaron los registros del dia: '.date('d-M-Y'));

    }
}
