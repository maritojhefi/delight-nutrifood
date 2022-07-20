<?php

namespace App\Helpers;

use App\Models\Plane;
use App\Models\Almuerzo;
use App\Helpers\WhatsappAPIHelper;
use Rawilk\Printing\Facades\Printing;




class GlobalHelper {

    public static function armarColeccionReporteDiario($pens, $fechaSeleccionada){

      $coleccion = collect();
      foreach ($pens as $lista) {
        //dd($lista);
        $detalle = $lista->detalle;
        if ($detalle != null) {
            $det = collect(json_decode($detalle, true));
            $sopaCustom = '';
            $det['SOPA'] == '' ? $sopaCustom = '0' : $sopaCustom = '1';

            $saberDia = WhatsappAPIHelper::saber_dia($fechaSeleccionada);
            $menu = Almuerzo::where('dia', $saberDia)->first();
            $tipoSegundo = '';
            $tipoEnvio = '';
            $det['PLATO']=rtrim($det['PLATO']);
            if ($det['PLATO'] == $menu->ejecutivo) $tipoSegundo = 'EJECUTIVO';
            if ($det['PLATO'] == $menu->dieta) $tipoSegundo = 'DIETA';
            if ($det['PLATO'] == $menu->vegetariano) $tipoSegundo = 'VEGGIE';

            if ($det['ENVIO'] == Plane::ENVIO1) $tipoEnvio = 'c) '.Plane::ENVIO1;
            if ($det['ENVIO'] == Plane::ENVIO2) $tipoEnvio = 'b) '.Plane::ENVIO2;
            if ($det['ENVIO'] == Plane::ENVIO3) $tipoEnvio = 'a) '.Plane::ENVIO3;
            $coleccion->push([


                'NOMBRE' => $lista->name,
                'SOPA' => $sopaCustom,
                'PLATO' => $tipoSegundo,
                'CARBOHIDRATO' => $det['CARBOHIDRATO'],
                'ENVIO' => $tipoEnvio,
                'ENSALADA' => 1,
                'EMPAQUE' => $det['EMPAQUE'],
                'JUGO' => 1,
                'ESTADO' => $lista->estado
            ]);
        } else {
            $coleccion->push([
                'NOMBRE' => $lista->name,
                'SOPA' => '',
                'PLATO' => '',
                'CARBOHIDRATO' => '',
                'ENVIO' => 'd) N/A',
                'ENSALADA' => '',
                'EMPAQUE' => '',
                'JUGO' => '',
                'ESTADO' => $lista->estado
            ]);
        }
    }
    $coleccion = $coleccion->sortBy(['ENVIO', 'asc']);
    return $coleccion;
    }
    public static function armarColeccionReporteDiarioVista($pens,$fechaSeleccionada)
    {

      $coleccion=collect();
      foreach ($pens as $lista) {
        //dd($lista);
        $detalle = $lista->detalle;
        if ($detalle != null) {
            $det = collect(json_decode($detalle, true));
            $sopaCustom = '';
            $det['SOPA'] == '' ? $sopaCustom = 'Sin Sopa' : $sopaCustom = $det['SOPA'];

            $saberDia = WhatsappAPIHelper::saber_dia($fechaSeleccionada);
            $menu = Almuerzo::where('dia', $saberDia)->first();
            $tipoSegundo = '';
            $tipoEnvio = '';
            
            $det['PLATO']=rtrim($det['PLATO']);
            if ($det['PLATO'] == $menu->ejecutivo) $tipoSegundo = 'EJECUTIVO';
            if ($det['PLATO'] == $menu->dieta) $tipoSegundo = 'DIETA';
            if ($det['PLATO'] == $menu->vegetariano) $tipoSegundo = 'VEGGIE';
            if ($det['ENVIO'] == Plane::ENVIO1) $tipoEnvio = 'c) '.Plane::ENVIO1;
            if ($det['ENVIO'] == Plane::ENVIO2) $tipoEnvio = 'b) '.Plane::ENVIO2;
            if ($det['ENVIO'] == Plane::ENVIO3) $tipoEnvio = 'a) '.Plane::ENVIO3;

            $coleccion->push([
                'ID' => $lista->id,
                'NOMBRE' => $lista->name,
                'ENSALADA' => $det['ENSALADA'],
                'SOPA' => $det['SOPA'],
                'PLATO' => $tipoSegundo,
                'CARBOHIDRATO' => $det['CARBOHIDRATO'],
                'JUGO' => $det['JUGO'],
                'ENVIO' => $tipoEnvio,
                'EMPAQUE' => $det['EMPAQUE'],
                'ESTADO' => $lista->estado,
                'PLAN' => $lista->nombre,
                'PLAN_ID'=>$lista->plane_id,
                'USER_ID'=>$lista->user_id
            ]);
        } else {
            $coleccion->push([
                'ID' => $lista->id,
                'NOMBRE' => $lista->name,
                'ENSALADA' => '',
                'SOPA' => '',
                'PLATO' => '',
                'CARBOHIDRATO' => '',
                'JUGO' => '',
                'ENVIO' => 'd) N/A',
                'EMPAQUE' => '',
                'ESTADO' => $lista->estado,
                'PLAN' => $lista->nombre,
                'PLAN_ID'=>$lista->plane_id,
                'USER_ID'=>$lista->user_id
            ]);
        }
    }
    $coleccion=$coleccion->sortBy(['ENVIO','asc']);
    return $coleccion;
    }
   
}