<?php

namespace App\Helpers;

use App\Models\Historial_venta;
use App\Models\Plane;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;


class CreateList
{


    static function crearlista(Venta $cuenta, $convenioService = null)
    {
        $registro = DB::table('producto_venta')->where('venta_id', $cuenta->id)->get();
        $cantidadItems = $registro->count();

        $personalizado = collect();
        $puntos = 0;
        $total = 0;
        $descuento = 0;
        $descuentoConvenio = 0;
        $totalOriginal = 0;
        $totaladicionalesGlobal = 0;
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

            $descuentoProducto = 0;
            $descuentoConvenioProducto = 0;
            $descuentosAplicados = [];

            // Precio original del producto (sin descuentos)
            $precioOriginal = $producto->precio;

            // Calcular descuento normal del producto
            if ($producto->descuento != 0) {
                $descuentoProducto = ($producto->precio - $producto->descuento) * $item->cantidad;
                $descuento = $descuento + ($producto->precio * $item->cantidad) - ($producto->descuento * $item->cantidad);
                $descuentosAplicados[] = "Descuento automático: " . number_format($descuentoProducto, 2) . " Bs";
            }

            // Calcular descuento de convenio si existe
            if ($convenioService && $cuenta->cliente) {
                $descuentoConvenioProducto = $convenioService->calcularDescuentoConvenio($producto, $cuenta->cliente, $item->cantidad);
                $descuentoConvenio += $descuentoConvenioProducto;

                // Debug temporal
                \Log::info('Calculando descuento convenio para producto: ' . $producto->id, [
                    'producto_nombre' => $producto->nombre,
                    'cliente_id' => $cuenta->cliente->id,
                    'cliente_nombre' => $cuenta->cliente->name,
                    'descuento_convenio_producto' => $descuentoConvenioProducto,
                    'tiene_convenio' => $convenioService->tieneDescuentoConvenio($producto, $cuenta->cliente)
                ]);

                if ($descuentoConvenioProducto > 0) {
                    $convenio = $convenioService->obtenerConvenioActivo($cuenta->cliente);
                    $tipoDescuento = $convenio->tipo_descuento === 'porcentaje' ? '%' : 'Bs';
                    $descuentosAplicados[] = "Convenio ({$convenio->nombre_convenio}): " . number_format($descuentoConvenioProducto, 2) . " Bs";
                }
            }

            $precioFinal = $producto->precioReal();
            if ($convenioService && $cuenta->cliente) {
                $precioFinal = $convenioService->obtenerPrecioConDescuentoConvenio($producto, $cuenta->cliente);
            }

            $subtotal = $precioFinal * $item->cantidad;
            $subtotal = $subtotal + $totaladicionales;

            // Crear texto descriptivo de descuentos
            $detalleDescuentos = empty($descuentosAplicados) ? '' : implode(' | ', $descuentosAplicados);

            // Determinar si hay descuentos aplicados
            $tieneDescuentos = !empty($descuentosAplicados);

            $personalizado->prepend([
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'medicion' => $producto->medicion,
                'cantidad' => $item->cantidad,
                'precio' => $precioFinal,
                'precio_original' => $precioOriginal,
                'subtotal' => $subtotal,
                'descuento_producto' => $descuentoProducto,
                'descuento_convenio' => $descuentoConvenioProducto,
                'detalle' => $detalleDescuentos,
                'tiene_descuentos' => $tieneDescuentos,
                'foto' => $producto->pathAttachment(),
                'total_adicionales' => $totaladicionales,
            ]);
            $totalOriginal = $totalOriginal + $precioOriginal * $item->cantidad;
            $puntos = $puntos + ($producto->puntos * $item->cantidad);
            $total = $total + $subtotal;
            $totaladicionalesGlobal = $totaladicionalesGlobal + $totaladicionales;
        }

        return [$personalizado, floatval($totalOriginal), floatval($cantidadItems), $puntos, floatval($descuento), floatval($descuentoConvenio), floatval($totaladicionalesGlobal)];
    }
    static function crearListaHistorico(Historial_venta $cuenta)
    {
        $registro = DB::table('historial_venta_producto')->where('historial_venta_id', $cuenta->id)->get();
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
                $descuento = $descuento + ($producto->precio * $item->cantidad) - ($producto->descuento * $item->cantidad);
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
        return [$personalizado, floatval($total), floatval($cantidadItems), $puntos, floatval($descuento)];
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
