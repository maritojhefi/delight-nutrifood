<?php

namespace App\Http\Controllers\Api;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Adicionale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Services\Ventas\Contracts\VentaServiceInterface;
use App\Services\Ventas\Contracts\ProductoVentaServiceInterface;
use App\Services\Ventas\Contracts\SaldoServiceInterface;
use App\Services\Ventas\Contracts\StockServiceInterface;
use App\Services\Ventas\Contracts\CalculadoraVentaServiceInterface;

class VentaController extends Controller
{
    public function __construct(
        private VentaServiceInterface $ventaService,
        private ProductoVentaServiceInterface $productoVentaService,
        private SaldoServiceInterface $saldoService,
        private StockServiceInterface $stockService,
        private CalculadoraVentaServiceInterface $calculadoraService
    ) {
        $this->middleware('auth');
    }

    /**
     * Obtener todas las ventas
     */
    public function index(): JsonResponse
    {
        try {
            $ventas = Venta::with(['cliente', 'sucursale', 'productos'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $ventas,
                'message' => 'Ventas obtenidas correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener ventas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva venta
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'sucursale_id' => 'required|integer|exists:sucursales,id',
                'cliente_id' => 'nullable|integer|exists:users,id'
            ]);

            $response = $this->ventaService->crearVenta(
                auth()->id(),
                $request->sucursale_id,
                $request->cliente_id
            );

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
                'message' => 'Error al crear venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una venta específica
     */
    public function show(Venta $venta): JsonResponse
    {
        try {
            $venta->load(['cliente', 'sucursale', 'productos.subcategoria', 'ventaHistorial']);
            
            // Calcular datos de la venta
            $calculos = $this->calculadoraService->calcularVenta($venta);

            return response()->json([
                'success' => true,
                'data' => [
                    'venta' => $venta,
                    'calculos' => $calculos->toArray()
                ],
                'message' => 'Venta obtenida correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar descuento de una venta
     */
    public function updateDescuento(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'descuento' => 'required|numeric|min:0'
            ]);

            $response = $this->ventaService->editarDescuento($venta, $request->descuento);

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
                'message' => 'Error al actualizar descuento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar cliente de una venta
     */
    public function updateCliente(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'cliente_id' => 'required|integer|exists:users,id'
            ]);

            $cliente = User::find($request->cliente_id);
            $response = $this->ventaService->cambiarClienteVenta($venta, $cliente);

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
                'message' => 'Error al cambiar cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agregar usuario manual a una venta
     */
    public function updateUsuarioManual(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'usuario_manual' => 'required|string|min:3'
            ]);

            $response = $this->ventaService->agregarUsuarioManual($venta, $request->usuario_manual);

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
                'message' => 'Error al agregar usuario manual: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar venta a cocina
     */
    public function enviarCocina(Venta $venta): JsonResponse
    {
        try {
            $response = $this->ventaService->enviarACocina($venta);

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar a cocina: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cobrar una venta
     */
    public function cobrar(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'metodos_seleccionados' => 'required|array',
                'total_acumulado' => 'required|numeric|min:0',
                'subtotal_con_descuento' => 'required|numeric|min:0',
                'descuento_saldo' => 'nullable|numeric|min:0'
            ]);

            $response = $this->ventaService->cobrarVenta(
                $venta,
                $request->metodos_seleccionados,
                $request->total_acumulado,
                $request->subtotal_con_descuento,
                $request->descuento_saldo ?? 0
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
                'message' => 'Error al cobrar venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar y finalizar una venta
     */
    public function cerrar(Venta $venta): JsonResponse
    {
        try {
            $response = $this->ventaService->cerrarVenta($venta);

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una venta
     */
    public function destroy(Venta $venta): JsonResponse
    {
        try {
            $response = $this->ventaService->eliminarVenta($venta);

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar venta: ' . $e->getMessage()
            ], 500);
        }
    }
}
