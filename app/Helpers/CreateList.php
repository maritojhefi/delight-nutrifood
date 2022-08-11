<?php

namespace App\Helpers;

use App\Models\Plane;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;


class CreateList
{


    static function crearlista(Venta $cuenta)
    {
        $registro = DB::table('producto_venta')->where('venta_id', $cuenta->id)->get();
        $cantidadItems = $registro->count();


        $personalizado = collect();
        $puntos = 0;
        $total = 0;
        $descuento = 0;
        foreach ($registro as $item) {
            $producto = Producto::find($item->producto_id);
            $adicionales = json_decode($item->adicionales, true);
            $totaladicionales = 0;
            if (isset($adicionales)) {

                foreach ($adicionales as $array) {
                    if (isset($array)) {
                        foreach ($array as $numero => $lista) {
                            foreach ($lista as $nombre => $precio) {
                                $totaladicionales = $totaladicionales + $precio;
                            }
                        }
                    }
                }
            }

            if ($producto->descuento != 0) {
                // $subtotal = $producto->descuento * $item->cantidad;
                // $subtotal = $subtotal + $totaladicionales;
                $descuento = $descuento + ($producto->precio*$item->cantidad)-($producto->descuento*$item->cantidad);
            }
            //     $personalizado->prepend([
            //         'id' => $producto->id, 
            //         'nombre' => $producto->nombre, 
            //         'medicion' => $producto->medicion, 
            //         'cantidad' => $item->cantidad, 
            //         'precio' => $producto->descuento,
            //         'subtotal' => $subtotal, 
            //         'foto' => $producto->pathAttachment()
            //     ]);
            // } else {
                $subtotal = $producto->precio * $item->cantidad;
                $subtotal = $subtotal + $totaladicionales;
                $personalizado->prepend([
                    'id' => $producto->id, 
                    'nombre' => $producto->nombre, 
                    'medicion' => $producto->medicion, 
                    'cantidad' => $item->cantidad, 
                    'precio' => $producto->precio, 
                    'subtotal' => $subtotal, 
                    'foto' => $producto->pathAttachment()
                ]);
            //}
            $puntos = $puntos + ($producto->puntos * $item->cantidad);
            
            $total = $total + $subtotal;
        }
        //$total=$total-$descuento;
        //dd($descuento);
        return [$personalizado, floatval($total), floatval($cantidadItems), $puntos,floatval($descuento)];
    }

    static function crearlistaantiguo(Venta $cuenta)
    {


        $listafiltrada = $cuenta->productos->pluck('nombre');

        $cantidadItems = $listafiltrada->count();
        $contando = $listafiltrada->countBy();

        $coleccion = collect($contando);

        $personalizado = collect();
        $total = 0;
        $puntos = 0;
        foreach ($coleccion as $nombre => $cantidad) {
            $producto = Producto::where('nombre', $nombre)->first();
            if ($producto->descuento != 0 && $producto->descuento != null) {
                $subtotal = $producto->descuento * $cantidad;
                $personalizado->prepend(['id' => $producto->id, 'nombre' => $nombre, 'cantidad' => $cantidad, 'precio' => $producto->descuento, 'subtotal' => $subtotal, 'foto' => $producto->pathAttachment()]);
            } else {
                $subtotal = $producto->precio * $cantidad;
                $personalizado->prepend(['id' => $producto->id, 'nombre' => $nombre, 'cantidad' => $cantidad, 'precio' => $producto->precio, 'subtotal' => $subtotal, 'foto' => $producto->pathAttachment()]);
            }

            $puntos = $puntos + ($producto->puntos * $cantidad);
            $total = $total + $subtotal;
        }
        return [$personalizado, $total, $cantidadItems, $puntos];
    }

    static function crearlistaplan($id)
    {
        $planes = DB::table('plane_user')
            ->where('user_id', $id)
            ->get();
        //dd($planes);
        $agrupado = $planes->pluck('plane_id');
        $contado = $agrupado->countBy();
        $coleccion = collect();
        foreach ($contado as $idplan => $cantidad) {
            $plan = Plane::find($idplan);
            $coleccion->push(['plan' => $plan->nombre, 'cantidad' => $cantidad, 'id' => $plan->id, 'editable' => $plan->editable]);
        }
        return $coleccion;
    }
}
