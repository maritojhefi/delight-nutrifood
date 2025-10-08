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
}