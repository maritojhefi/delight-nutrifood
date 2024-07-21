<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Plane;
use App\Models\Almuerzo;
use App\Helpers\WhatsappAPIHelper;
use Rawilk\Printing\Facades\Printing;




class GlobalHelper
{
    public static function timeago($date)
    {
        $timestamp = strtotime($date);

        $strTime = ['segundo', 'minuto', 'hora', 'dia', 'mes', 'año'];
        $length = ['60', '60', '24', '30', '12', '10'];

        $currentTime = time();
        if ($currentTime >= $timestamp) {
            $diff = time() - $timestamp;
            for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);
            return 'Hace ' . $diff . ' ' . $strTime[$i] . '(s)';
        }
    }
    public static function saber_dia($nombredia)
    {
        //dd(date('N', strtotime($nombredia)));
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }
    public static function fechaFormateada(int $level, $fecha = null)
    {
        switch ($level) {
            case 1:
                $formato = 'dddd D';
                break;
            case 2:
                $formato = 'dddd D \d\e MMMM';
                break;
            case 3:
                $formato = 'dddd D \d\e MMMM \d\e\l Y';
                break;
            case 4:
                $formato = 'D \d\e MMMM';
                break;
            case 5:
                $formato = 'D \d\e MMMM \d\e\l Y';
                break;
            case 6:
                $formato = 'hh:mm a';
                break;
            case 7:
                $formato = 'dddd D \d\e MMMM hh:mm a';
                break;
            default:
                $formato = 'dddd D \d\e MMMM \d\e\l Y';
                break;
        }
        if ($fecha != null) {
            return ucfirst(Carbon::parse($fecha)->locale('es')->isoFormat($formato));
        } else {
            return ucfirst(Carbon::now()->locale('es')->isoFormat($formato));
        }
    }
    public static function armarColeccionReporteDiario($pens, $fechaSeleccionada)
    {

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

                $det['PLATO'] = str_replace(' ', '', $det['PLATO']);

                $menu->ejecutivo = str_replace(' ', '', $menu->ejecutivo);
                $menu->dieta = str_replace(' ', '', $menu->dieta);
                $menu->vegetariano = str_replace(' ', '', $menu->vegetariano);

                if ($det['PLATO'] == $menu->ejecutivo) $tipoSegundo = 'EJECUTIVO';
                if ($det['PLATO'] == $menu->dieta) $tipoSegundo = 'DIETA';
                if ($det['PLATO'] == $menu->vegetariano) $tipoSegundo = 'VEGGIE';

                if ($det['ENVIO'] == Plane::ENVIO1) $tipoEnvio = 'c) ' . Plane::ENVIO1;
                if ($det['ENVIO'] == Plane::ENVIO2) $tipoEnvio = 'b) ' . Plane::ENVIO2;
                if ($det['ENVIO'] == Plane::ENVIO3) $tipoEnvio = 'a) ' . Plane::ENVIO3;
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
    public static function armarColeccionReporteDiarioVista($pens, $fechaSeleccionada)
    {

        $coleccion = collect();
        foreach ($pens as $lista) {
            // dd($lista);
            $detalle = $lista->detalle;
            if ($detalle != null) {
                $det = collect(json_decode($detalle, true));
                $sopaCustom = '';
                $det['SOPA'] == '' ? $sopaCustom = 'Sin Sopa' : $sopaCustom = $det['SOPA'];

                $saberDia = WhatsappAPIHelper::saber_dia($fechaSeleccionada);
                $menu = Almuerzo::where('dia', $saberDia)->first();
                $tipoSegundo = '';
                $tipoEnvio = '';

                $det['PLATO'] = str_replace(' ', '', $det['PLATO']);
                $det['SOPA'] = trim($det['SOPA']);
                $det['CARBOHIDRATO'] = trim($det['CARBOHIDRATO']);
                $menu->ejecutivo = str_replace(' ', '', $menu->ejecutivo);
                $menu->dieta = str_replace(' ', '', $menu->dieta);
                $menu->vegetariano = str_replace(' ', '', $menu->vegetariano);

                if ($det['PLATO'] == $menu->ejecutivo) $tipoSegundo = 'EJECUTIVO';
                if ($det['PLATO'] == $menu->dieta) $tipoSegundo = 'DIETA';
                if ($det['PLATO'] == $menu->vegetariano) $tipoSegundo = 'VEGGIE';
                if ($det['ENVIO'] == Plane::ENVIO1) $tipoEnvio = 'c.- ' . Plane::ENVIO1;
                if ($det['ENVIO'] == Plane::ENVIO2) $tipoEnvio = 'b.- ' . Plane::ENVIO2;
                if ($det['ENVIO'] == Plane::ENVIO3) $tipoEnvio = 'a.- ' . Plane::ENVIO3;

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
                    'COCINA' => $lista->cocina,
                    'PLAN' => $lista->nombre,
                    'PLAN_ID' => $lista->plane_id,
                    'USER_ID' => $lista->user_id
                ]);
            } else if ($lista->estado == Plane::ESTADOPERMISO) {
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
                    'COCINA' => $lista->cocina,
                    'PLAN' => $lista->nombre,
                    'PLAN_ID' => $lista->plane_id,
                    'USER_ID' => $lista->user_id
                ]);
            }
        }
        $coleccion = $coleccion->sortBy(['ENVIO', 'asc']);
        return $coleccion;
    }
    public static function menuDiarioArray($sopa, $plato, $ensalada, $carbohidrato, $jugo, $envio = Plane::ENVIO1, $empaque = 'Ninguno')
    {
        return array(
            'SOPA' => $sopa,
            'PLATO' => $plato,
            'ENSALADA' => $ensalada,
            'CARBOHIDRATO' => $carbohidrato,
            'JUGO' => $jugo,
            'ENVIO' => $envio,
            'EMPAQUE' => $empaque
        );
    }
}
