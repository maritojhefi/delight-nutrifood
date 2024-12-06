<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Plane;
use App\Models\Almuerzo;
use App\Helpers\GlobalHelper;
use Illuminate\Console\Command;
use App\Helpers\WhatsappAPIHelper;
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
        DB::table('plane_user')->whereDate('start', Carbon::today())->where('estado', Plane::ESTADODESARROLLO)->update(['estado' => Plane::ESTADOPENDIENTE, 'color' => Plane::COLORPENDIENTE, 'detalle' => null]);
        // Paso 1: Obtener los registros que coinciden con los criterios
        $records = DB::table('plane_user')
            ->select('plane_user.*', 'planes.sopa')
            ->leftJoin('planes', 'planes.id', '=', 'plane_user.plane_id')
            ->whereDate('start', Carbon::today())
            ->whereIn('estado', [Plane::ESTADODESARROLLO, Plane::ESTADOPENDIENTE])
            ->where('plane_user.detalle', null)
            ->where('editable', true)
            ->get();

        // Paso 2: Barajar los registros para obtener un orden aleatorio
        $shuffledRecords = $records->shuffle();

        // Paso 3: Dividir los registros en dos grupos
        $totalRecords = $shuffledRecords->count();
        $half = intval($totalRecords / 2);

        $firstHalf = $shuffledRecords->slice(0, $half);
        $secondHalf = $shuffledRecords->slice($half);

        // Paso 4: Preparar los valores de detalle
        $diaActual = WhatsappAPIHelper::saber_dia(Carbon::today());
        $ejecutivo = Almuerzo::select('sopa', 'ejecutivo', 'ejecutivo_tiene_carbo', 'carbohidrato_1', 'ensalada', 'jugo')->where('dia', $diaActual)->first()->toArray();
        $dieta = Almuerzo::select('sopa', 'dieta', 'dieta_tiene_carbo', 'carbohidrato_2', 'ensalada', 'jugo')->where('dia', $diaActual)->first()->toArray();



        if ($ejecutivo) {
            // Paso 5: Actualizar los registros en dos grupos usando foreach
            foreach ($firstHalf as $record) {

                if ($record->sopa == true && $ejecutivo) {
                    $planEjecutivo = GlobalHelper::menuDiarioArray($ejecutivo['sopa'], $ejecutivo['ejecutivo'], $ejecutivo['ensalada'], $ejecutivo['ejecutivo_tiene_carbo'] == true ? $ejecutivo['carbohidrato_1'] : 'sin carbohidrato', $ejecutivo['jugo']);
                } else {
                    $planEjecutivo = GlobalHelper::menuDiarioArray('', $ejecutivo['ejecutivo'], $ejecutivo['ensalada'], $ejecutivo['ejecutivo_tiene_carbo'] == true ? $ejecutivo['carbohidrato_1'] : 'sin carbohidrato', $ejecutivo['jugo']);
                }
                $detalle = json_encode($planEjecutivo);
                DB::table('plane_user')
                    ->where('id', $record->id)
                    ->update(['estado' => Plane::ESTADOFINALIZADO, 'color' => Plane::COLORFINALIZADO, 'detalle' => $detalle]);
            }
        }

        if ($dieta) {
            foreach ($secondHalf as $record) {

                if ($record->sopa == true) {
                    $planDieta = GlobalHelper::menuDiarioArray($dieta['sopa'], $dieta['dieta'], $dieta['ensalada'], $dieta['dieta_tiene_carbo'] == true ? $dieta['carbohidrato_2'] : 'sin carbohidrato', $dieta['jugo']);
                } else {
                    $planDieta = GlobalHelper::menuDiarioArray('', $dieta['dieta'], $dieta['ensalada'], $dieta['dieta_tiene_carbo'] == true ? $dieta['carbohidrato_2'] : 'sin carbohidrato', $dieta['jugo']);
                }
                $detalle = json_encode($planDieta);
                DB::table('plane_user')
                    ->where('id', $record->id)
                    ->update(['estado' => Plane::ESTADOFINALIZADO, 'color' => Plane::COLORFINALIZADO, 'detalle' => $detalle]);
            }
        }


        // Actualizar los registros restantes sin detalle específico

        DB::table('plane_user')->whereDate('start', Carbon::today())->where('estado', Plane::ESTADOPENDIENTE)->update(['estado' => Plane::ESTADOFINALIZADO, 'color' => Plane::COLORFINALIZADO]);

        DB::table('whatsapp_plan_almuerzos')->delete();

        $this->info('Se finalizaron los registros del dia: ' . date('d-M-Y'));
    }
}
