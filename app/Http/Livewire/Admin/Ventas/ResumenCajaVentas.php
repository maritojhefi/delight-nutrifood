<?php

namespace App\Http\Livewire\Admin\Ventas;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\Egreso;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Models\RegistroStockCaja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ResumenCajaVentas extends Component
{
    public $cajaActiva;

    // Propiedades para el formulario de egresos
    public $detalle_egreso;
    public $monto_egreso;
    public $metodo_pago_id_egreso;

    // Propiedades para control de stock
    public $productos_seleccionados = [];
    public $detalle_general_stock;

    protected $listeners = [
        'echo:actualizar-datos-caja,ActualizarDatosCajaEvent' => 'actualizarDatosCaja',
        'ventasHoy' => 'ventasHoy',
        'gastosHoy' => 'gastosHoy',
        'guardarEgreso' => 'guardarEgreso',
        'abrirControlStock' => 'abrirControlStock',
        'buscarProducto' => 'buscarProducto',
        'confirmarReajustes' => 'confirmarReajustes',
    ];
    public function actualizarDatosCaja($event)
    {
        $this->dispatchBrowserEvent('toastAlert', [
            'type' => $event['type'],
            'message' => $event['message'],
        ]);
    }

    public function ventasHoy()
    {
        if (session()->has('caja_activa')) {
            $cajaActiva = Caja::find(session('caja_activa'));
        } else {
            $cajaActiva = Caja::whereDate('created_at', Carbon::today())->where('estado', 'abierto')->first();
        }

        if (!$cajaActiva) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'warning',
                'message' => 'No hay caja activa',
            ]);
            return;
        }

        // Obtener datos para el sweet alert
        $totalVentas = $cajaActiva->ingresoVentasPOS();
        $ventasPorMetodo = $cajaActiva->ingresosPorMetodoPagoDeVentas();
        $ventasPorCajero = $cajaActiva->ingresosPorCajeroDeVentas();
        $productosVendidos = $cajaActiva->arrayProductosVendidos();

        $this->dispatchBrowserEvent('mostrarResumenVentas', [
            'totalVentas' => $totalVentas,
            'ventasPorMetodo' => $ventasPorMetodo,
            'ventasPorCajero' => $ventasPorCajero,
            'productosVendidos' => $productosVendidos,
        ]);
    }

    public function gastosHoy()
    {
        if (session()->has('caja_activa')) {
            $cajaActiva = Caja::find(session('caja_activa'));
        } else {
            $cajaActiva = Caja::whereDate('created_at', Carbon::today())->where('estado', 'abierto')->first();
        }

        if (!$cajaActiva) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'warning',
                'message' => 'No hay caja activa',
            ]);
            return;
        }

        // Obtener datos de egresos
        $egresos = $cajaActiva->egresos()->with('metodoPago')->orderBy('created_at', 'desc')->get();
        $totalEgresos = $cajaActiva->egresos->sum('monto');
        $metodosPago = MetodoPago::all();

        $this->dispatchBrowserEvent('mostrarResumenGastos', [
            'totalEgresos' => $totalEgresos,
            'egresos' => $egresos,
            'metodosPago' => $metodosPago,
            'cajaId' => $cajaActiva->id,
        ]);
    }

    public function guardarEgreso($detalle, $monto, $metodoPagoId)
    {
        // dd($detalle, $monto, $metodoPagoId);
        $this->detalle_egreso = $detalle;
        $this->monto_egreso = $monto;
        $this->metodo_pago_id_egreso = $metodoPagoId;
        // $this->validate([
        //     'detalle_egreso' => 'nullable|string|max:255',
        //     'monto_egreso' => 'required|numeric|min:0.01',
        //     'metodo_pago_id_egreso' => 'required|exists:metodos_pagos,id',
        // ], [
        //     'monto_egreso.required' => 'El monto es obligatorio',
        //     'monto_egreso.numeric' => 'El monto debe ser un número',
        //     'monto_egreso.min' => 'El monto debe ser mayor a 0',
        //     'metodo_pago_id_egreso.required' => 'Debe seleccionar un método de pago',
        //     'metodo_pago_id_egreso.exists' => 'El método de pago no existe',
        // ]);

        if (session()->has('caja_activa')) {
            $cajaActiva = Caja::find(session('caja_activa'));
        } else {
            $cajaActiva = Caja::whereDate('created_at', Carbon::today())->where('estado', 'abierto')->first();
        }

        if (!$cajaActiva) {
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'No hay caja activa',
            ]);
            return;
        }

        Egreso::create([
            'caja_id' => $cajaActiva->id,
            'detalle' => $this->detalle_egreso ?? 'Sin detalle',
            'monto' => $this->monto_egreso,
            'metodo_pago_id' => $this->metodo_pago_id_egreso,
        ]);

        // Limpiar campos
        $this->reset(['detalle_egreso', 'monto_egreso', 'metodo_pago_id_egreso']);

        $this->dispatchBrowserEvent('toastAlert', [
            'type' => 'success',
            'message' => 'Egreso registrado correctamente',
        ]);

        // Recargar el modal con los datos actualizados
        $this->gastosHoy();
    }

    public function abrirControlStock()
    {
        // Obtener productos del último registro de stock
        $productosPreCargados = $this->obtenerProductosUltimoRegistro();

        $this->dispatchBrowserEvent('mostrarControlStock', [
            'productosPreCargados' => $productosPreCargados
        ]);
    }

    private function obtenerProductosUltimoRegistro()
    {
        // Obtener el último registro de stock de cualquier caja
        $ultimoRegistro = RegistroStockCaja::with('productos')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$ultimoRegistro) {
            return [];
        }

        // Obtener los productos con su stock actual
        $productosPreCargados = [];

        foreach ($ultimoRegistro->productos as $producto) {
            // Verificar que el producto siga activo y tenga stock
            if ($producto->estado !== 'activo') {
                continue;
            }

            $stockActual = $producto->stockTotal();

            // Solo incluir productos con stock disponible
            if ($stockActual > 0) {
                $productosPreCargados[] = [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'stock_actual' => $stockActual,
                    'vendidos_caja' => $this->obtenerCantidadVendidaEnCaja($producto->id),
                ];
            }
        }

        return $productosPreCargados;
    }

    public function buscarProducto($termino)
    {
        if (strlen($termino) < 2) {
            $this->dispatchBrowserEvent('resultadosBusqueda', ['productos' => []]);
            return;
        }

        $productos = Producto::where('nombre', 'like', '%' . $termino . '%')
            ->orWhere('codigoBarra', 'like', '%' . $termino . '%')
            ->where('estado', 'activo')
            ->with('sucursale')
            ->limit(10)
            ->get()
            ->map(function ($producto) {
                $stockTotal = $producto->stockTotal();
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'stock_actual' => $stockTotal,
                    'vendidos_caja' => $this->obtenerCantidadVendidaEnCaja($producto->id),
                    'tiene_stock' => $stockTotal > 0, // Nuevo campo para validar
                ];
            });

        $this->dispatchBrowserEvent('resultadosBusqueda', ['productos' => $productos]);
    }

    private function obtenerCantidadVendidaEnCaja($productoId)
    {
        if (session()->has('caja_activa')) {
            $cajaId = session('caja_activa');
        } else {
            $cajaActiva = Caja::whereDate('created_at', Carbon::today())->where('estado', 'abierto')->first();
            $cajaId = $cajaActiva ? $cajaActiva->id : null;
        }

        if (!$cajaId) {
            return 0;
        }

        return DB::table('historial_venta_producto')
            ->join('historial_ventas', 'historial_venta_producto.historial_venta_id', '=', 'historial_ventas.id')
            ->where('historial_ventas.caja_id', $cajaId)
            ->where('historial_venta_producto.producto_id', $productoId)
            ->sum('historial_venta_producto.cantidad');
    }

    public function confirmarReajustes($productosReajustados, $detalleGeneral)
    {
        try {
            if (session()->has('caja_activa')) {
                $cajaActiva = Caja::find(session('caja_activa'));
            } else {
                $cajaActiva = Caja::whereDate('created_at', Carbon::today())->where('estado', 'abierto')->first();
            }

            if (!$cajaActiva) {
                $this->dispatchBrowserEvent('toastAlert', [
                    'type' => 'error',
                    'message' => 'No hay caja activa',
                ]);
                return;
            }

            DB::beginTransaction();

            // Crear registro principal
            $registro = RegistroStockCaja::create([
                'caja_id' => $cajaActiva->id,
                'detalle' => $detalleGeneral,
                'usuario_id' => Auth::id(),
            ]);

            // Procesar cada producto
            foreach ($productosReajustados as $productoData) {
                $producto = Producto::find($productoData['producto_id']);
                if (!$producto) {
                    $this->dispatchBrowserEvent('toastAlert', [
                        'type' => 'warning',
                        'message' => 'Producto no encontrado: ID ' . $productoData['producto_id'],
                    ]);
                    continue;
                }

                $stockActual = $producto->stockTotal();
                $nuevoStock = $productoData['nuevo_stock'];
                $diferencia = $nuevoStock - $stockActual;

                // Determinar acción
                $accion = 'sin_cambio';
                $cantidadCambio = 0;

                if ($diferencia > 0) {
                    $accion = 'aumento';
                    $cantidadCambio = $diferencia;
                    $this->aumentarStock($producto, $diferencia, $cajaActiva->sucursale_id);
                } elseif ($diferencia < 0) {
                    $accion = 'disminucion';
                    $cantidadCambio = abs($diferencia);
                    $this->disminuirStock($producto, abs($diferencia), $cajaActiva->sucursale_id);
                }

                // Registrar en la tabla pivot
                $registro->productos()->attach($producto->id, [
                    'accion' => $accion,
                    'cantidad' => $cantidadCambio,
                    'detalle' => $productoData['detalle'] ?? 'Sin detalle específico',
                ]);
            }

            DB::commit();

            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'success',
                'message' => 'Reajustes registrados correctamente',
            ]);

            // Cerrar modal solo si fue exitoso
            $this->dispatchBrowserEvent('cerrarControlStock');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('toastAlert', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    private function aumentarStock($producto, $cantidad, $sucursalId)
    {
        // Buscar el lote más reciente para aumentar
        $lote = DB::table('producto_sucursale')
            ->where('producto_id', $producto->id)
            ->where('sucursale_id', $sucursalId)
            ->orderBy('fecha_venc', 'desc')
            ->first();

        if (!$lote) {
            throw new \Exception("No se puede aumentar stock de {$producto->nombre}. No hay lotes existentes. Debe crear el lote desde la sección de Almacén.");
        }

        // Aumentar en el lote más reciente
        DB::table('producto_sucursale')
            ->where('id', $lote->id)
            ->increment('cantidad', $cantidad);
    }

    private function disminuirStock($producto, $cantidad, $sucursalId)
    {
        $cantidadRestante = $cantidad;

        // Obtener lotes ordenados por fecha de vencimiento (FIFO)
        $lotes = DB::table('producto_sucursale')
            ->where('producto_id', $producto->id)
            ->where('sucursale_id', $sucursalId)
            ->where('cantidad', '>', 0)
            ->orderBy('fecha_venc', 'asc')
            ->get();

        foreach ($lotes as $lote) {
            if ($cantidadRestante <= 0) break;

            if ($lote->cantidad >= $cantidadRestante) {
                // Este lote tiene suficiente
                DB::table('producto_sucursale')
                    ->where('id', $lote->id)
                    ->decrement('cantidad', $cantidadRestante);
                $cantidadRestante = 0;
            } else {
                // Usar todo este lote y continuar
                $cantidadRestante -= $lote->cantidad;
                DB::table('producto_sucursale')
                    ->where('id', $lote->id)
                    ->update(['cantidad' => 0]);
            }
        }

        if ($cantidadRestante > 0) {
            throw new \Exception("Stock insuficiente. Faltan {$cantidadRestante} unidades.");
        }
    }

    public function render()
    {
        if (session()->has('caja_activa')) {
            $this->cajaActiva = Caja::find(session('caja_activa'));
        } else {
            $this->cajaActiva = Caja::whereDate('created_at', Carbon::today())->where('estado', 'abierto')->first();
        }
        return view('livewire.admin.ventas.resumen-caja-ventas');
    }
}
