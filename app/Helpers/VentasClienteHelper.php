<?php

namespace App\Helpers;

use App\Models\Adicionale;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentasClienteHelper
{
    protected $cuenta;

    public function __construct(Venta $cuenta)
    {
        $this->cuenta = $cuenta;
    }
    
    public function adicionar(Producto $producto, Collection $extras)
    {
        // if ($this->cuenta->pagado == true) {
        //     $this->dispatchBrowserEvent('alert', [
        //         'type' => 'warning',
        //         'message' => 'La venta ya ha sido pagada, no se puede modificar',
        //     ]);
        //     return false;
        // }
        Log::debug("Modelo Eloquent del producto recibido desde el controlador:", [$producto]);
        Log::debug("Collecion extras recibida desde el controlador:", [$extras]);

        if ($producto->contable == true) {
            $resultado = $this->actualizarstock($producto, 'sumar', 1);
            if ($resultado == null) {
                // $this->dispatchBrowserEvent('alert', [
                //     'type' => 'warning',
                //     'message' => 'No se puede agregar porque no existe stock para este producto',
                // ]);

                // Throw para error, no existe stock
                Log::error('Error el actualizar el stock');
            } else {
                $cuenta = Venta::find($this->cuenta->id);
                // Buscar registros en producto_venta que coincidan con el producto actual
                $registro = DB::table('producto_venta')->where('producto_id', $producto->id)->where('venta_id', $cuenta->id)->get();

                if ($registro->count() == 0) {
                    // De no existir, se genera un nuevo registro en la tabla producto_venta con el id del producto actual
                    $cuenta->productos()->attach($producto->id);
                } else {
                    // De existir, se actualiza el producto_venta existente y se incrementa la cantidad
                    DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->increment('cantidad', 1);
                }
                //actualiza lista de adicionales en el atributo
                if ($producto->medicion == 'unidad') {
                    $this->actualizaradicionales($producto->id, 'sumar', $extras);
                }

                // $this->dispatchBrowserEvent('alert', [
                //     'type' => 'success',
                //     'message' => 'Se agrego 1 ' . $producto->nombre . ' a esta venta',
                // ]);
                // $this->actualizarlista($cuenta);
            }
        } else {
            $cuenta = Venta::find($this->cuenta->id);
            $registro = DB::table('producto_venta')->where('producto_id', $producto->id)->where('venta_id', $cuenta->id)->get();

            if ($registro->count() == 0) {
                $cuenta->productos()->attach($producto->id);
            } else {
                DB::table('producto_venta')->where('venta_id', $cuenta->id)->where('producto_id', $producto->id)->increment('cantidad', 1);
            }
            //actualiza lista de adicionales en el atributo
            if ($producto->medicion == 'unidad') {
                $this->actualizaradicionales($producto->id, 'sumar', $extras);
            }

            // $this->dispatchBrowserEvent('alert', [
            //     'type' => 'success',
            //     'message' => 'Se agrego 1 ' . $producto->nombre . ' a esta venta',
            // ]);
            // $this->actualizarlista($cuenta);
        }
    }

    protected function actualizarstock(Producto $producto, $operacion, $cant)
    {
        $consulta = DB::table('producto_sucursale')->where('producto_id', $producto->id)->where('sucursale_id', $this->cuenta->sucursale_id)->orderBy('fecha_venc', 'asc')->get();
        $stock = $consulta->where('cantidad', '!=', 0)->first();
        $cantidadtotal = $consulta->pluck('cantidad');
        $sumado = $cantidadtotal->sum();

        if ($consulta == null) {
            return null;
        } else {
            switch ($operacion) {
                case 'sumar':
                    if ($stock == null) {
                        return null;
                    } else {
                        $restado = $stock->cantidad - $cant;
                        DB::table('producto_sucursale')
                            ->where('id', $stock->id)
                            ->update(['cantidad' => $restado]);
                    }

                    break;
                case 'restar':
                    $consultarestar = $consulta->sortByDesc('fecha_venc');

                    foreach ($consultarestar as $array) {
                        $espacio = $array->max - $array->cantidad;

                        if ($espacio != 0) {
                            if ($espacio >= $cant) {
                                DB::table('producto_sucursale')->where('id', $array->id)->increment('cantidad', $cant);
                                break;
                            } else {
                                $cant = $cant - $espacio;
                                DB::table('producto_sucursale')
                                    ->where('id', $array->id)
                                    ->update(['cantidad' => $array->max]);
                            }
                        }
                    }
                    break;
                case 'sumarvarios':
                    if ($sumado > $cant) {
                        foreach ($consulta as $array) {
                            if ($array->cantidad > $cant) {
                                DB::table('producto_sucursale')->where('id', $array->id)->decrement('cantidad', $cant);
                                break;
                            } else {
                                $cant = $cant - $array->cantidad;
                                DB::table('producto_sucursale')
                                    ->where('id', $array->id)
                                    ->update(['cantidad' => 0]);
                            }
                        }
                    } else {
                        return false;
                    }
                    break;
            }
            return true;
        }
    }

    public function actualizaradicionales($idproducto, $operacion, $extras)
    {
        Log::debug("Coleccion de extras recibida por actualizaradicionales",[$extras]);
        // Usa 'first()' para obtener un solo registro, más eficiente que 'get()' y [0]
        $registro = DB::table('producto_venta')
            ->where('producto_id', $idproducto)
            ->where('venta_id', $this->cuenta->id)
            ->first();

        if ($registro) {
            // $listaadicionales = $extras;
            $listaActual = $registro->adicionales;

            // Decodifica el JSON si existe, de lo contrario, inicializa un arreglo vacío
            if ($listaActual) {
                $json = json_decode($listaActual, true);
            } else {
                $json = [];
            }
            // if ($listaActual && $listaActual->isNotEmpty()) {
            //     $json = json_decode($listaActual, true);
            // } else {
            //     $json = [];
            // }

            // Determina la siguiente clave numérica
            $siguiente_clave = count($json) + 1;

            if ($operacion == 'sumar') {
                // Asigna explícitamente la nueva clave numérica.
                // Esto mantiene la estructura de objeto JSON.

                if ($extras->isEmpty()) {
                    $json[$siguiente_clave] = [];
                } else {
                    $array_extras = [];
                    // por cada extras, crear un registro
                    foreach ($extras as $true_adicional) {
                        if ($true_adicional->contable == true && $true_adicional->cantidad == 0) {
                            // Throw para error, no hay stock del adicional
                        } else if ($true_adicional->contable == true && $true_adicional->cantidad >= 1) {
                            $true_adicional->decrement('cantidad');
                        }
                        array_push($array_extras, [$true_adicional->nombre => $true_adicional->precio]);
                    }
                    Log::debug("Contenido de array_extras: ",[$array_extras]);
                    $json[$siguiente_clave] = $array_extras;
                }
                // Codifica el arreglo de vuelta a JSON antes de guardar
                $adicionales_json = json_encode($json);

                DB::table('producto_venta')
                    ->where('producto_id', $idproducto)
                    ->where('venta_id', $this->cuenta->id)
                    ->update(['adicionales' => $adicionales_json]);
            }
        }
    }

    // protected function agregarAdicional(Adicionale $adicional) {
    //     if ($adicional->contable == true && $adicional->cantidad == 0) {
    //         // $this->dispatchBrowserEvent('alert', [
    //         //     'type' => 'warning',
    //         //     'message' => 'No existe stock para ' . $adicional->nombre,
    //         // ]);

    //         // No existe stock para el adicional que se intenta agregar
    //         // Throw para error, no hay stock del adicional
    //     }

    //     return [$adicional->nombre => $adicional->precio];
    // }

    public function actualizaradicionales2($idproducto, $operacion, $extras)
    {
        // Usa 'first()' para obtener un solo registro, más eficiente que 'get()' y [0]
        $registro = DB::table('producto_venta')
            ->where('producto_id', $idproducto)
            ->where('venta_id', $this->cuenta->id)
            ->first();

        if ($registro) {
            $listaadicionales = $registro->adicionales;
            // Decodifica el JSON si existe, de lo contrario, inicializa un arreglo vacío
            if ($listaadicionales) {
                $json = json_decode($listaadicionales, true);
            } else {
                $json = [];
            }

            
            // Determina la siguiente clave numérica
            $siguiente_clave = count($json) + 1;

            if ($operacion == 'sumar') {
                // Asigna explícitamente la nueva clave numérica.
                // Esto mantiene la estructura de objeto JSON.
                $json[$siguiente_clave] = [];

                // Codifica el arreglo de vuelta a JSON antes de guardar
                $adicionales_json = json_encode($json);

                DB::table('producto_venta')
                    ->where('producto_id', $idproducto)
                    ->where('venta_id', $this->cuenta->id)
                    ->update(['adicionales' => $adicionales_json]);
            }
            // elseif ($operacion == 'muchos') {
            //     for ($i = 0; $i < $this->cantidadespecifica; $i++) {
            //         // Asigna nuevas claves numéricas en cada iteración
            //         $json[count($json) + 1] = [];
            //     }

            //     $adicionales_json = json_encode($json);
                
            //     DB::table('producto_venta')
            //         ->where('producto_id', $idproducto)
            //         ->where('venta_id', $this->cuenta->id)
            //         ->update(['adicionales' => $adicionales_json]);
            // }
            elseif ($registro->cantidad > 0) {
                // Esta lógica parece ser para una operación de "restar" o "quitar"
                // Se debe asegurar que $siguiente_clave - 1 es la clave que se quiere eliminar
                $clave_a_eliminar = count($json);
                
                if (isset($json[$clave_a_eliminar])) {
                    foreach ($json[$clave_a_eliminar] as $pos => $adic) {
                        $adicional = Adicionale::where('nombre', key($adic))->first();
                        if ($adicional && $adicional->contable) {
                            $adicional->increment('cantidad');
                            $adicional->save();
                            GlobalHelper::actualizarMenuCantidadDesdePOS($adicional, 'aumentar');
                        }
                    }
                    unset($json[$clave_a_eliminar]);
                }
                
                $adicionales_json = json_encode($json);

                DB::table('producto_venta')
                    ->where('producto_id', $idproducto)
                    ->where('venta_id', $this->cuenta->id)
                    ->update(['adicionales' => $adicionales_json]);
            }
        }
    }
}