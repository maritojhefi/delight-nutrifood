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


class ProductosHelper
{
    // public function verificarStockGeneral(Producto $producto, $adicionales_ids, $cantidadSolicitada) {
    //     $stockProductoDisponible = $this->obtenerStockProducto($producto);
    //     $adicionalesObservados = $this->obtenerAdicionalesAgotados($adicionales_ids,$cantidadSolicitada);

    //     if (!empty($adicionalesObservados)) {
    //         $agotados = collect($adicionalesObservados['agotados']);
    //         $limitados = collect($adicionalesObservados['limitados']);
    //         $cantidadMaxima = $adicionalesObservados['cantidadMaxima'];

    //         if (!empty($agotados) || !empty($limitados) || $stockProductoDisponible < $cantidadSolicitada) {
    //             throw new HttpResponseException(response()->json([
    //                 'success' => false,
    //                 'messageAgotados' => "Los siguientes adicionales se encuentran agotados: {$agotados->pluck('nombre')->implode(', ')}",
    //                 'messageLimitados' => "Stock disponible: {$limitados->map(fn($item) => "{$item['nombre']} ({$item['stock']})")->implode(', ')}.
    //                     Puedes actualizar tu orden presionando el boton de abajo.",
    //                 'idsAdicionalesAgotados' => $agotados->pluck('id')->all(),
    //                 'idsAdicionalesLimitados' => $limitados->pluck('id')->all(),
    //                 'stockProducto' => $stockProductoDisponible,
    //                 'cantidadMaxima' => $cantidadMaxima
    //             ], Response::HTTP_UNPROCESSABLE_ENTITY));
    //         }
    //     }
    // }
        public function verificarStockGeneral(Producto $producto, array $adicionales_ids, int $cantidadSolicitada) 
    {
        // Always check both product and adicionales stock to get complete picture
        $stockProductoDisponible = $this->obtenerStockProducto($producto);
        $adicionalesObservados = empty($adicionales_ids) ? [] : $this->obtenerAdicionalesAgotados($adicionales_ids, $cantidadSolicitada);
        
        // Determine the true maximum quantity possible considering ALL constraints
        $cantidadMaximaPosible = $stockProductoDisponible;
        
        // Factor in adicionales limitations
        if (!empty($adicionalesObservados)) {
            $cantidadMaximaAdicionales = $adicionalesObservados['cantidadMaxima'];
            if ($cantidadMaximaAdicionales !== null) {
                $cantidadMaximaPosible = min($cantidadMaximaPosible, $cantidadMaximaAdicionales);
            }
        }

        // Check if the requested quantity is feasible
        $stockProductoInsuficiente = $stockProductoDisponible < $cantidadSolicitada;
        $adicionalesProblematicos = !empty($adicionalesObservados);

        if ($stockProductoInsuficiente || $adicionalesProblematicos) {
            $response = [
                'success' => false,
                'stockProducto' => $stockProductoDisponible,
                'cantidadSolicitada' => $cantidadSolicitada,
                'cantidadMaximaPosible' => $cantidadMaximaPosible,
                'idsAdicionalesAgotados' => [],
                'idsAdicionalesLimitados' => [],
                'messageAgotados' => null,
                'messageLimitados' => null,
                'messageProducto' => null,
            ];

            // Handle product stock issues
            if ($stockProductoInsuficiente) {
                $response['messageProducto'] = "Stock disponible: {$stockProductoDisponible}, Solicitado: {$cantidadSolicitada}";
            }

            // Handle adicionales issues
            if ($adicionalesProblematicos) {
                $agotados = collect($adicionalesObservados['agotados']);
                $limitados = collect($adicionalesObservados['limitados']);

                if ($agotados->isNotEmpty()) {
                    $response['messageAgotados'] = "Los siguientes adicionales se encuentran agotados: {$agotados->pluck('nombre')->implode(', ')}";
                    $response['idsAdicionalesAgotados'] = $agotados->pluck('id')->all();
                }

                if ($limitados->isNotEmpty()) {
                    $response['messageLimitados'] = "Stock limitado para adicionales: {$limitados->map(fn($item) => "{$item['nombre']} ({$item['stock']})")->implode(', ')}. Puedes actualizar tu orden presionando el botón de abajo.";
                    $response['idsAdicionalesLimitados'] = $limitados->pluck('id')->all();
                }
            }

            throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
        }
    }
    private function obtenerStockProducto (Producto $producto) {
        // Si el producto no es contable, el stock es infinito
        if (!$producto->contable) {
            return PHP_FLOAT_MAX;
        }

        $consulta = DB::table('producto_sucursale')
                ->where('producto_id', $producto->id)
                // HARDCODEADO SUCURSALE_ID = 1
                ->where('sucursale_id', 1)
                ->get();
        $sumado = $consulta->sum('cantidad');
        // Retornar el stock disponible
        return $sumado;
    }   

    private function obtenerAdicionalesAgotados(array $adicionales_ids, int $cantidadSolicitada): array
    {
        if (empty($adicionales_ids)) {
            return [];
        }

        // Fetch all adicionales in one query instead of individual queries
        $adicionales = Adicionale::whereIn('id', $adicionales_ids)->get()->keyBy('id');
        
        $agotados = [];
        $limitados = [];
        $cantidadMaxima = PHP_FLOAT_MAX;

        foreach ($adicionales_ids as $adicionalId) {
            $adicional = $adicionales->get($adicionalId);
            
            if (!$adicional) {
                // Handle missing adicional
                $agotados[] = [
                    'id' => $adicionalId,
                    'nombre' => "Item ID: {$adicionalId} (no encontrado)",
                ];
                continue;
            }

            // Skip non-contable adicionales (infinite stock)
            if (!$adicional->contable) {
                continue;
            }

            if ($adicional->cantidad <= 0) {
                $agotados[] = [
                    'id' => $adicionalId,
                    'nombre' => $adicional->nombre,
                ];
            } else if ($adicional->cantidad < $cantidadSolicitada) {
                $limitados[] = [
                    'id' => $adicionalId,
                    'nombre' => $adicional->nombre,
                    'stock' => $adicional->cantidad,
                ];

                // Track the minimum available quantity
                if ($adicional->cantidad < $cantidadMaxima) {
                    $cantidadMaxima = $adicional->cantidad;
                }
            }
        }

        // Reset cantidadMaxima if no limitations were found
        if ($cantidadMaxima == PHP_FLOAT_MAX) {
            $cantidadMaxima = null;
        }

        // Return empty array if no issues found
        if (empty($agotados) && empty($limitados)) {
            return [];
        }

        return [
            "agotados" => $agotados, 
            "limitados" => $limitados, 
            "cantidadMaxima" => $cantidadMaxima
        ];    
    }

    // private function obtenerAdicionalesAgotados($adicionales_ids, $cantidadSolicitada) {
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

    //     if (empty($agotados) && empty($limitados)) {
    //         return [];
    //     }

    //     return ["agotados" => $agotados, "limitados" => $limitados, "cantidadMaxima" => $cantidadMaxima];    
    // }
}