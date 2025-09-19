<?php

namespace App\Services\Ventas;

use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use App\Services\Ventas\DTOs\VentaResponse;
use App\Services\Ventas\Exceptions\VentaException;
use App\Services\Ventas\Contracts\StockServiceInterface;
use App\Services\Ventas\DTOs\StockVerificationResponse;
use Illuminate\Database\Eloquent\Collection;

class StockService implements StockServiceInterface
{
    public function actualizarStock(Producto $producto, string $operacion, int $cantidad, int $sucursalId): VentaResponse
    {
        try {
            $consulta = DB::table('producto_sucursale')
                ->where('producto_id', $producto->id)
                ->where('sucursale_id', $sucursalId)
                ->orderBy('fecha_venc', 'asc')
                ->get();

            if ($consulta->isEmpty()) {
                return VentaResponse::error("No hay registros de stock para {$producto->nombre}");
            }

            switch ($operacion) {
                case 'sumar':
                    return $this->sumarStock($consulta, $cantidad);
                    
                case 'restar':
                    return $this->restarStock($consulta, $cantidad);
                    
                case 'sumarvarios':
                    return $this->sumarVariosStock($consulta, $cantidad);
                    
                default:
                    return VentaResponse::error('Operación de stock no válida');
            }
        } catch (\Exception $e) {
            return VentaResponse::error('Error al actualizar stock: ' . $e->getMessage());
        }
    }

    public function verificarStock(Producto $producto, int $cantidad, int $sucursalId): bool
    {
        if (!$producto->contable) {
            return true;
        }

        $stockTotal = $this->obtenerStockTotal($producto, $sucursalId);
        return $stockTotal >= $cantidad;
    }

    public function obtenerStockTotal(Producto $producto, int $sucursalId): int
    {
        if (!$producto->contable) {
            return PHP_INT_MAX;
        }

        return DB::table('producto_sucursale')
            ->where('producto_id', $producto->id)
            ->where('sucursale_id', $sucursalId)
            ->sum('cantidad');
    }

    private function sumarStock($consulta, int $cantidad): VentaResponse
    {
        $stock = $consulta->where('cantidad', '!=', 0)->first();
        
        if (!$stock) {
            return VentaResponse::error('No hay stock disponible');
        }

        $restado = $stock->cantidad - $cantidad;
        
        if ($restado < 0) {
            return VentaResponse::error('Stock insuficiente');
        }

        DB::table('producto_sucursale')
            ->where('id', $stock->id)
            ->update(['cantidad' => $restado]);

        return VentaResponse::success(null, 'Stock actualizado correctamente');
    }

    private function restarStock($consulta, int $cantidad): VentaResponse
    {
        $consultaRestar = $consulta->sortByDesc('fecha_venc');

        foreach ($consultaRestar as $array) {
            $espacio = $array->max - $array->cantidad;

            if ($espacio > 0) {
                if ($espacio >= $cantidad) {
                    DB::table('producto_sucursale')
                        ->where('id', $array->id)
                        ->increment('cantidad', $cantidad);
                    break;
                } else {
                    $cantidad = $cantidad - $espacio;
                    DB::table('producto_sucursale')
                        ->where('id', $array->id)
                        ->update(['cantidad' => $array->max]);
                }
            }
        }

        return VentaResponse::success(null, 'Stock restaurado correctamente');
    }

    private function sumarVariosStock($consulta, int $cantidad): VentaResponse
    {
        $cantidadTotal = $consulta->sum('cantidad');
        
        if ($cantidadTotal < $cantidad) {
            return VentaResponse::error('Stock insuficiente para la cantidad solicitada');
        }

        foreach ($consulta as $array) {
            if ($cantidad <= 0) break;
            
            if ($array->cantidad >= $cantidad) {
                DB::table('producto_sucursale')
                    ->where('id', $array->id)
                    ->decrement('cantidad', $cantidad);
                $cantidad = 0;
            } else {
                $cantidad = $cantidad - $array->cantidad;
                DB::table('producto_sucursale')
                    ->where('id', $array->id)
                    ->update(['cantidad' => 0]);
            }
        }

        return VentaResponse::success(null, 'Stock actualizado correctamente');
    }

    public function verificarStockCompleto(
        Producto $producto, 
        Collection $adicionales, 
        int $cantidadSolicitada, 
        int $sucursalId = 1
    ): StockVerificationResponse {
        
        $stockProducto = $this->obtenerStockTotal($producto, $sucursalId);
        $adicionalesInfo = $this->analizarAdicionalesStock($adicionales, $cantidadSolicitada, $sucursalId);
        
        // Calcular cantidad máxima posible considerando todas las restricciones
        $cantidadMaxima = $stockProducto;
        if ($adicionalesInfo['cantidadMaxima'] !== null) {
            $cantidadMaxima = min($cantidadMaxima, $adicionalesInfo['cantidadMaxima']);
        }
        
        $stockSuficiente = $stockProducto >= $cantidadSolicitada && 
                        empty($adicionalesInfo['agotados']) && 
                        empty($adicionalesInfo['limitados']);
        
        return new StockVerificationResponse($stockSuficiente,
            $stockProducto,
            $cantidadSolicitada,
            $cantidadMaxima,
            [
                'suficiente' => $stockProducto >= $cantidadSolicitada,
                'disponible' => $stockProducto
            ],
            $adicionalesInfo
        );
    }

    public function analizarAdicionalesStock(
        Collection $adicionales, 
        int $cantidadSolicitada, 
        int $sucursalId = 1
    ): array {
        
        if ($adicionales->isEmpty()) {
            return [
                'agotados' => [],
                'limitados' => [],
                'cantidadMaxima' => null,
                'todosSuficientes' => true
            ];
        }

        $agotados = [];
        $limitados = [];
        $cantidadMaxima = PHP_INT_MAX;

        foreach ($adicionales as $adicional) {
            if (!$adicional->contable) {
                continue;
            }

            $stockAdicional = $adicional->cantidad;

            if ($stockAdicional <= 0) {
                $agotados[] = [
                    'id' => $adicional->id,
                    'nombre' => $adicional->nombre,
                    'stock' => $stockAdicional
                ];
            } elseif ($stockAdicional < $cantidadSolicitada) {
                $limitados[] = [
                    'id' => $adicional->id,
                    'nombre' => $adicional->nombre,
                    'stock' => $stockAdicional,
                    'solicitado' => $cantidadSolicitada
                ];
                
                $cantidadMaxima = min($cantidadMaxima, $stockAdicional);
            }
        }

        return [
            'agotados' => $agotados,
            'limitados' => $limitados,
            'cantidadMaxima' => $cantidadMaxima === PHP_INT_MAX ? null : $cantidadMaxima,
            'todosSuficientes' => empty($agotados) && empty($limitados)
        ];
    }
}
