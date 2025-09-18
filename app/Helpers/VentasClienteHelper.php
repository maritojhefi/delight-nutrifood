<?php

namespace App\Helpers;

use App\Models\Adicionale;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class VentasClienteHelper
{
    protected $cuenta;

    public function __construct(Venta $cuenta)
    {
        $this->cuenta = $cuenta;
    }

    // FLUJO DE TRABAJO
    // REVISAR STOCK DISPONIBLE
    // TRANSACCION AGREGAR ODENES
    // ACTUALIZAR STOCK DISPONIBLE

    public function adicionar(Producto $producto, Collection $extras, int $cantidad)
    {
        // Revisar existencia de stock
        $stockDisponible = $this->verificarStock($producto, $cantidad);
        if (! $stockDisponible) {
            throw new HttpResponseException(response()->json([
                'message' => 'El producto solicitado no tiene stock disponible para la cantidad requerida.'
            ], Response::HTTP_NOT_FOUND));
        }
        // De existir stock, iniciar una transaccion para agregar registro de la orden
        DB::beginTransaction();
        try {
            // Revisar si existe un producto_venta apropiado para la cuenta
            if ($this->cuenta->productos()->where('producto_id', $producto->id)->exists()) {
                // Si existe, incrementar su cantidad por la cantidad total
                $this->cuenta->productos()
                    ->updateExistingPivot($producto->id, [
                        'cantidad' => DB::raw("cantidad + {$cantidad}")
                    ]);
            } else {
                // Si no existe, crear nuevo registro con la cantidad total
                $this->cuenta->productos()->attach($producto->id, ['cantidad' => $cantidad]);
            }

            // Controlar adicionales por cada orden si su medicion es 'unidad'
            if ($producto->medicion == 'unidad') {
                for ($i = 0; $i < $cantidad; $i++) {
                    $this->actualizarAdicionales($producto->id, 'sumar', $extras);
                }
            }

            // Una vez completada la transaccion de manera exitosa, actualizar el stock disponible del producto
            // De ser necesario, actualizar el stock del producto
            if ($producto->contable == true) {
                $this->actualizarStockProducto($producto,'reducirStock', $cantidad);
            }

            DB::commit();
        } catch (\Exception  $e) {
            // Rollback en caso de error
            DB::rollBack();
            
            // Re-throw the exception or handle it appropriately
            throw new HttpResponseException(response()->json([
                'message' => 'Error al procesar la orden: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }
    protected function verificarStock (Producto $producto, int $cantidad) {
        // Si el producto no es contable, el stock es infinito
        if (!$producto->contable) {
            return true;
        }

        $consulta = DB::table('producto_sucursale')
                ->where('producto_id', $producto->id)
                ->where('sucursale_id', $this->cuenta->sucursale_id)
                ->get();
        $sumado = $consulta->sum('cantidad');

        // Si sumado (suma de stocks en registros producto_sucursale) es mayor a la cantidad solicitada
        // Retornamos true (stock suficiente), caso contrario, retornamos false (stock insuficiente)
        return $sumado >= $cantidad;
    }

    // public function verificarStockAdicionales($adicionales_ids, $cantidadSolicitada) {
    //     $agotados = [];
    //     $limitados = [];
    //     $cantidadMaxima = PHP_FLOAT_MAX;

    //     foreach ($adicionales_ids as $adicionalId) {
    //         $adicional = Adicionale::find($adicionalId);
            
    //         if (!$adicional || ($adicional->contable && $adicional->cantidad <= 0)) {
    //             $agotados[] = [
    //                 'id' => $adicionalId,
    //                 'nombre' => $adicional ? $adicional->nombre : "Item ID: {$adicionalId}",
    //             ];
    //         } else if ($adicional->contable && $adicional->cantidad < $cantidadSolicitada) {
    //             $limitados[] = [
    //                 'id' => $adicionalId,
    //                 'nombre' => $adicional ? $adicional->nombre : "Item ID: {$adicionalId}",
    //                 'stock' => $adicional->cantidad,
    //             ];

    //             if ($adicional->cantidad < $cantidadMaxima) {
    //                 $cantidadMaxima = $adicional->cantidad;
    //             }
    //         }
    //     }

    //     if ($cantidadMaxima == PHP_FLOAT_MAX) {
    //         $cantidadMaxima = null;
    //     }

    //     if (!empty($agotados) || !empty($limitados)) {
    //         throw new HttpResponseException(response()->json([
    //             'success' => false,
    //             'messageAgotados' => "Los siguientes adicionales se encuentran agotados: {$agotados->pluck('nombre')->implode(', ')}",
    //             'messageLimitados' => "Stock disponible: {$limitados->map(fn($item) => "{$item['nombre']} ({$item['stock']})")->implode(', ')}.
    //                 Puedes actualizar tu orden presionando el boton de abajo.",
    //                 // 'messageLimitados' => "El stock para: {$limitados->pluck('nombre')->implode(', ')}; es bajo, puedes actualizar tu orden presionando el boton de abajo.",
    //             'idsAdicionalesAgotados' => $agotados->pluck('id')->all(),
    //             'idsAdicionalesLimitados' => $limitados->pluck('id')->all(),
    //             'cantidadMaxima' => $cantidadMaxima
    //         ], Response::HTTP_UNPROCESSABLE_ENTITY));
    //     }
    // }

    protected function actualizarStockProducto(Producto $producto, $operacion, $cantidad)
    {
        $registrosStock = DB::table('producto_sucursale')
        ->where('producto_id', $producto->id)
        ->where('sucursale_id', $this->cuenta->sucursale_id)
        ->orderBy('fecha_venc', 'asc')
        ->get();

        if ($registrosStock->isEmpty()) {
            throw new \Exception("No se encontraron registros de stock para el producto en esta sucursal.");
        }

        switch ($operacion) {
            case 'reducirStock':
                $this->reducirStock($registrosStock, $cantidad);
                break;
            case 'agregarStock':
                $this->agregarStock($registrosStock, $cantidad);
                break;
            // case 'reducirVarios':
            //     $this->reducirStock($registrosStock, $cantidad);
            //     break;
            default:
                throw new \InvalidArgumentException("Operación no válida: {$operacion}");
        }

        return true;
    }

    private function reducirStock($registosStock, int $cantidad)
    {
        $pendienteReducir = $cantidad;

        foreach ($registosStock as $stock) {
            if ($pendienteReducir <= 0) {
                break;
            }

            if ($stock->cantidad > 0) {
                $montoReducir = min($stock->cantidad, $pendienteReducir);

                DB::table('producto_sucursale')
                ->where('id', $stock->id)
                ->decrement('cantidad', $montoReducir);

                $pendienteReducir -= $montoReducir;
            }
        }

        if ($pendienteReducir > 0) {
            throw new \Exception("Stock insuficiente. Faltan {$pendienteReducir} unidades.");
        }
    }

    private function agregarStock($registosStock, int $cantidad)
    {
        $pendienteIncrementar  = $cantidad;

        foreach ($registosStock as $stock) {
            if ($pendienteIncrementar <= 0) {
                break;
            }

            $espacioDisponible = $stock->max - $stock->cantidad;

            if ($espacioDisponible > 9) {
                $montoIncrementar = min($espacioDisponible, $pendienteIncrementar);

                DB::table('producto_sucursale')
                ->where('id', $stock->id)
                ->increment('cantidad', $montoIncrementar);

                $pendienteIncrementar -= $montoIncrementar;
            }
        }
        if ($pendienteIncrementar > 0) {
            throw new \Exception("No hay suficiente espacio para devolver {$pendienteIncrementar} unidades.");
        }
    }

    protected function actualizarAdicionales($idproducto, $operacion, $extras)
    {
        // Obtenemos el primer registro correspondiente al idproducto en producto_venta
        $productoVenta = $this->cuenta->productos()
        ->where('producto_id', $idproducto)
        ->first();

        if ($productoVenta) {
            // El listado actual son las ordenes registradas en el producto_venta antes
            // de su actualizacion
            $listaActual = $productoVenta->pivot->adicionales;

            // Decodificat el JSON si existe, de lo contrario, inicializa un arreglo vacío
            $json = $listaActual ? json_decode($listaActual, true) : [];

            // Determina la siguiente clave numérica
            $siguiente_clave = count($json) + 1;

            if ($operacion == 'sumar') {
                // Asigna explícitamente la nueva clave numérica.
                // Esto mantiene la estructura de objeto JSON.
                if ($extras->isEmpty()) {
                    // Si extras esta vacio, se agrega una nueva orden sin detalles
                    $json[$siguiente_clave] = [];
                } else {
                    $array_extras = [];
                    
                    // Validar stock para todos los adicionales
                    foreach ($extras as $adicional) {
                        if ($adicional->contable == true && $adicional->cantidad == 0) {
                            throw new \Exception("No hay stock disponible para el adicional: {$adicional->nombre}");
                        }
                    }
                    // Por cada extra, crear un detalle y asignarlo a una nueva orden (clave en producto_venta.adicionales)
                    foreach ($extras as $adicional) {
                        if ($adicional->contable == true && $adicional->cantidad >= 1) {
                            $adicional->decrement('cantidad');
                        }
                        $array_extras[] = [$adicional->nombre => $adicional->precio];
                    }
                    $json[$siguiente_clave] = $array_extras;
                }
                // Codifica el arreglo de vuelta a JSON antes de guardar
                $this->cuenta->productos()
                ->updateExistingPivot($idproducto, [
                    'adicionales' => json_encode($json)
                ]);
            }
        }
    }
    
    // public function adicionar(Producto $producto, Collection $extras)
    // {
    //     // if ($this->cuenta->pagado == true) {
    //     //     $this->dispatchBrowserEvent('alert', [
    //     //         'type' => 'warning',
    //     //         'message' => 'La venta ya ha sido pagada, no se puede modificar',
    //     //     ]);
    //     //     return false;
    //     // }
    //     if ($producto->contable == true) {
    //         $resultado = $this->actualizarstock($producto, 'sumar', 1);
    //         if ($resultado == null) {
    //             // De no existir stock para el producto, devolver error 404
    //             throw new HttpResponseException(response()->json([
    //                 'message' => 'El producto solicitado no tiene stock disponible.'
    //             ],   Response::HTTP_NOT_FOUND));
    //         } else {
    //             $cuenta = Venta::find($this->cuenta->id);
    //             // Buscar registros en producto_venta que coincidan con el producto actual
    //             $registro = DB::table('producto_venta')->where('producto_id', $producto->id)->where('venta_id', $cuenta->id)->get();

    //             if ($registro->count() == 0) {
    //                 // De no existir, se genera un nuevo registro en la tabla producto_venta con el id del producto actual
    //                 $cuenta->productos()->attach($producto->id);
    //             } else {
    //                 // De existir, se actualiza el producto_venta existente y se incrementa la cantidad
    //                 DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->increment('cantidad', 1);
    //             }
    //             //actualiza lista de adicionales en el atributo
    //             if ($producto->medicion == 'unidad') {
    //                 $this->actualizaradicionales($producto->id, 'sumar', $extras);
    //             }
    //         }
    //     } else {
    //         $cuenta = Venta::find($this->cuenta->id);
    //         $registro = DB::table('producto_venta')->where('producto_id', $producto->id)->where('venta_id', $cuenta->id)->get();

    //         if ($registro->count() == 0) {
    //             $cuenta->productos()->attach($producto->id);
    //         } else {
    //             DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->increment('cantidad', 1);
    //         }
    //         //actualiza lista de adicionales en el atributo
    //         if ($producto->medicion == 'unidad') {
    //             $this->actualizaradicionales($producto->id, 'sumar', $extras);
    //         }
    //     }
    // }


    // protected function actualizarstock(Producto $producto, $operacion, $cant)
    // {
    //     // Se hace busqueda del producto en la tabla producto_sucursale
    //     // Tomando en cuenta que pertenezca a la misma sucursal que la venta
    //     $consulta = DB::table('producto_sucursale')->where('producto_id', $producto->id)->where('sucursale_id', $this->cuenta->sucursale_id)->orderBy('fecha_venc', 'asc')->get();
    //     // Se obtiene el primer registro con cantidad distinta a 0
    //     // REFACTORIZAR
    //     $stock = $consulta->where('cantidad', '!=', 0)->first();
    //     $cantidadtotal = $consulta->pluck('cantidad');
    //     $sumado = $cantidadtotal->sum();

    //     if ($consulta == null) {
    //         return null;
    //         // Throw new error
    //     } else {
    //         switch ($operacion) {
    //             case 'sumar':
    //                 if ($stock == null) {
    //                     return null;
    //                 } else {
    //                     $restado = $stock->cantidad - $cant;
    //                     DB::table('producto_sucursale')
    //                         ->where('id', $stock->id)
    //                         ->update(['cantidad' => $restado]);
    //                 }

    //                 break;
    //             case 'restar':
    //                 $consultarestar = $consulta->sortByDesc('fecha_venc');

    //                 foreach ($consultarestar as $array) {
    //                     $espacio = $array->max - $array->cantidad;

    //                     if ($espacio != 0) {
    //                         if ($espacio >= $cant) {
    //                             DB::table('producto_sucursale')->where('id', $array->id)->increment('cantidad', $cant);
    //                             break;
    //                         } else {
    //                             $cant = $cant - $espacio;
    //                             DB::table('producto_sucursale')
    //                                 ->where('id', $array->id)
    //                                 ->update(['cantidad' => $array->max]);
    //                         }
    //                     }
    //                 }
    //                 break;
    //             case 'sumarvarios':
    //                 if ($sumado > $cant) {
    //                     foreach ($consulta as $array) {
    //                         if ($array->cantidad > $cant) {
    //                             DB::table('producto_sucursale')->where('id', $array->id)->decrement('cantidad', $cant);
    //                             break;
    //                         } else {
    //                             $cant = $cant - $array->cantidad;
    //                             DB::table('producto_sucursale')
    //                                 ->where('id', $array->id)
    //                                 ->update(['cantidad' => 0]);
    //                         }
    //                     }
    //                 } else {
    //                     return false;
    //                 }
    //                 break;
    //         }
    //         return true;
    //     }
    // }

    // public function actualizaradicionales2($idproducto, $operacion, $extras)
    // {
    //     // Usa 'first()' para obtener un solo registro, más eficiente que 'get()' y [0]
    //     $registro = DB::table('producto_venta')
    //         ->where('producto_id', $idproducto)
    //         ->where('venta_id', $this->cuenta->id)
    //         ->first();

    //     if ($registro) {
    //         $listaadicionales = $registro->adicionales;
    //         // Decodifica el JSON si existe, de lo contrario, inicializa un arreglo vacío
    //         if ($listaadicionales) {
    //             $json = json_decode($listaadicionales, true);
    //         } else {
    //             $json = [];
    //         }

            
    //         // Determina la siguiente clave numérica
    //         $siguiente_clave = count($json) + 1;

    //         if ($operacion == 'sumar') {
    //             // Asigna explícitamente la nueva clave numérica.
    //             // Esto mantiene la estructura de objeto JSON.
    //             $json[$siguiente_clave] = [];

    //             // Codifica el arreglo de vuelta a JSON antes de guardar
    //             $adicionales_json = json_encode($json);

    //             DB::table('producto_venta')
    //                 ->where('producto_id', $idproducto)
    //                 ->where('venta_id', $this->cuenta->id)
    //                 ->update(['adicionales' => $adicionales_json]);
    //         }
    //         // elseif ($operacion == 'muchos') {
    //         //     for ($i = 0; $i < $this->cantidadespecifica; $i++) {
    //         //         // Asigna nuevas claves numéricas en cada iteración
    //         //         $json[count($json) + 1] = [];
    //         //     }

    //         //     $adicionales_json = json_encode($json);
                
    //         //     DB::table('producto_venta')
    //         //         ->where('producto_id', $idproducto)
    //         //         ->where('venta_id', $this->cuenta->id)
    //         //         ->update(['adicionales' => $adicionales_json]);
    //         // }
    //         elseif ($registro->cantidad > 0) {
    //             // Esta lógica parece ser para una operación de "restar" o "quitar"
    //             // Se debe asegurar que $siguiente_clave - 1 es la clave que se quiere eliminar
    //             $clave_a_eliminar = count($json);
                
    //             if (isset($json[$clave_a_eliminar])) {
    //                 foreach ($json[$clave_a_eliminar] as $pos => $adic) {
    //                     $adicional = Adicionale::where('nombre', key($adic))->first();
    //                     if ($adicional && $adicional->contable) {
    //                         $adicional->increment('cantidad');
    //                         $adicional->save();
    //                         GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar');
    //                     }
    //                 }
    //                 unset($json[$clave_a_eliminar]);
    //             }
                
    //             $adicionales_json = json_encode($json);

    //             DB::table('producto_venta')
    //                 ->where('producto_id', $idproducto)
    //                 ->where('venta_id', $this->cuenta->id)
    //                 ->update(['adicionales' => $adicionales_json]);
    //         }
    //     }
    // }
}