<?php

namespace App\Http\Controllers\Api;

use App\Models\Venta;
use App\Models\Saldo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Services\Ventas\Contracts\SaldoServiceInterface;

class SaldoController extends Controller
{
    public function __construct(
        private SaldoServiceInterface $saldoService
    ) {
        $this->middleware('auth');
    }

    /**
     * Registrar un nuevo saldo para un cliente
     */
    public function store(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'monto' => 'required|numeric|min:0',
                'detalle' => 'required|string|max:500',
                'tipo' => 'required|integer|exists:metodo_pagos,id',
                'es_deuda' => 'nullable|boolean'
            ]);

            $response = $this->saldoService->registrarSaldo(
                $venta,
                $request->monto,
                $request->detalle,
                $request->tipo,
                $request->es_deuda ?? false
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
                'message' => 'Error al registrar saldo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Anular o reactivar un saldo
     */
    public function anular(Saldo $saldo): JsonResponse
    {
        try {
            $response = $this->saldoService->anularSaldo($saldo);

            return response()->json($response->toArray(), $response->success ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al anular saldo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener el máximo descuento de saldo permitido para una venta
     */
    public function maximoDescuento(Venta $venta): JsonResponse
    {
        try {
            $maximo = $this->saldoService->calcularMaximoDescuentoSaldo($venta);

            return response()->json([
                'success' => true,
                'data' => [
                    'maximo_descuento_saldo' => $maximo
                ],
                'message' => 'Máximo descuento calculado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular máximo descuento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar si se puede aplicar un descuento de saldo
     */
    public function validarDescuento(Request $request, Venta $venta): JsonResponse
    {
        try {
            $request->validate([
                'descuento' => 'required|numeric|min:0'
            ]);

            $response = $this->saldoService->validarDescuentoSaldo($venta, $request->descuento);

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
                'message' => 'Error al validar descuento: ' . $e->getMessage()
            ], 500);
        }
    }
}
