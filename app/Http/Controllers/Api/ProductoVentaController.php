<?php

namespace App\Http\Controllers\Api;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Adicionale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Services\Ventas\Contracts\ProductoVentaServiceInterface;
use App\Services\Ventas\Contracts\CalculadoraVentaServiceInterface;

class ProductoVentaController extends Controller
{
    public function __construct(
        private ProductoVentaServiceInterface $productoVentaService,
        private CalculadoraVentaServiceInterface $calculadoraService
    ) {
        $this->middleware('auth');
    }

    /**
     * Agregar producto a una venta
     */
    public function store(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|exists:productos,id',
                'cantidad' => 'nullable|integer|min:1|max:100'
            ]);

            $producto = Producto::find($request->producto_id);
            $cantidad = $request->cantidad ?? 1;

            $response = $this->productoVentaService->agregarProducto($venta, $producto, $cantidad);

            if ($response->success) {
                // Recalcular datos de la venta
                $venta->refresh();
                $calculos = $this->calculadoraService->calcularVenta($venta);
                $response->data = [
                    'venta' => $venta->load(['productos']),
                    'calculos' => $calculos->toArray()
                ];
            }

            return response()->json($response->toArray(), $response->success ? 201 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una unidad de producto de la venta
     */
    public function eliminarUno(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|exists:productos,id'
            ]);

            $producto = Producto::find($request->producto_id);
            $response = $this->productoVentaService->eliminarUnoProducto($venta, $producto);

            if ($response->success) {
                // Recalcular datos de la venta
                $venta->refresh();
                $calculos = $this->calculadoraService->calcularVenta($venta);
                $response->data = [
                    'venta' => $venta->load(['productos']),
                    'calculos' => $calculos->toArray()
                ];
            }

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar completamente un producto de la venta
     */
    public function destroy(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|exists:productos,id'
            ]);

            $producto = Producto::find($request->producto_id);
            $response = $this->productoVentaService->eliminarProductoCompleto($venta, $producto);

            if ($response->success) {
                // Recalcular datos de la venta
                $venta->refresh();
                $calculos = $this->calculadoraService->calcularVenta($venta);
                $response->data = [
                    'venta' => $venta->load(['productos']),
                    'calculos' => $calculos->toArray()
                ];
            }

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agregar adicional a un producto en la venta
     */
    public function agregarAdicional(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|exists:productos,id',
                'adicional_id' => 'required|integer|exists:adicionales,id',
                'item' => 'required|integer|min:1'
            ]);

            $producto = Producto::find($request->producto_id);
            $adicional = Adicionale::find($request->adicional_id);

            $response = $this->productoVentaService->agregarAdicional(
                $venta, 
                $producto, 
                $adicional, 
                $request->item
            );

            if ($response->success) {
                // Recalcular datos de la venta
                $venta->refresh();
                $calculos = $this->calculadoraService->calcularVenta($venta);
                $response->data = [
                    'venta' => $venta->load(['productos']),
                    'calculos' => $calculos->toArray()
                ];
            }

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar adicional: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un item específico de un producto
     */
    public function eliminarItem(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|exists:productos,id',
                'posicion' => 'required|integer|min:1'
            ]);

            $producto = Producto::find($request->producto_id);

            $response = $this->productoVentaService->eliminarItem(
                $venta, 
                $producto, 
                $request->posicion
            );

            if ($response->success) {
                // Recalcular datos de la venta
                $venta->refresh();
                $calculos = $this->calculadoraService->calcularVenta($venta);
                $response->data = [
                    'venta' => $venta->load(['productos']),
                    'calculos' => $calculos->toArray()
                ];
            }

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar observación en un producto
     */
    public function guardarObservacion(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|exists:productos,id',
                'observacion' => 'required|string|max:500'
            ]);

            $producto = Producto::find($request->producto_id);

            $response = $this->productoVentaService->guardarObservacion(
                $venta, 
                $producto, 
                $request->observacion
            );

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar observación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agregar producto desde un plan
     */
    public function agregarDesdeplan(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'plan_id' => 'required|integer|exists:planes,id',
                'producto_id' => 'required|integer|exists:productos,id'
            ]);

            $response = $this->productoVentaService->agregarDesdeplan(
                $venta, 
                $request->user_id, 
                $request->plan_id, 
                $request->producto_id
            );

            if ($response->success) {
                // Recalcular datos de la venta
                $venta->refresh();
                $calculos = $this->calculadoraService->calcularVenta($venta);
                $response->data = [
                    'venta' => $venta->load(['productos']),
                    'calculos' => $calculos->toArray()
                ];
            }

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar desde plan: ' . $e->getMessage()
            ], 500);
        }
    }
}
