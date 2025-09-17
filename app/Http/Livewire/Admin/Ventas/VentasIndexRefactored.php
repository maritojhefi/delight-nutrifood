<?php

namespace App\Http\Livewire\Admin\Ventas;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Adicionale;
use Livewire\Component;
use App\Services\Ventas\Contracts\VentaServiceInterface;
use App\Services\Ventas\Contracts\SaldoServiceInterface;
use App\Services\Ventas\Contracts\StockServiceInterface;
use App\Services\Ventas\Contracts\ProductoVentaServiceInterface;
use App\Services\Ventas\Contracts\CalculadoraVentaServiceInterface;

class VentasIndexRefactored extends Component
{
    // Propiedades del componente (UI)
    public $cuenta;
    public $search;
    public $metodosSeleccionados = [];
    public $totalAcumuladoMetodos = 0;
    public $descuentoSaldo = 0;
    
    // Services inyectados
    private VentaServiceInterface $ventaService;
    private ProductoVentaServiceInterface $productoVentaService;
    private SaldoServiceInterface $saldoService;
    private StockServiceInterface $stockService;
    private CalculadoraVentaServiceInterface $calculadoraService;

    public function boot(
        VentaServiceInterface $ventaService,
        ProductoVentaServiceInterface $productoVentaService,
        SaldoServiceInterface $saldoService,
        StockServiceInterface $stockService,
        CalculadoraVentaServiceInterface $calculadoraService
    ) {
        $this->ventaService = $ventaService;
        $this->productoVentaService = $productoVentaService;
        $this->saldoService = $saldoService;
        $this->stockService = $stockService;
        $this->calculadoraService = $calculadoraService;
    }

    /**
     * Ejemplo de cómo usar el service para agregar un producto
     */
    public function adicionar(Producto $producto)
    {
        $response = $this->productoVentaService->agregarProducto($this->cuenta, $producto);
        
        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->actualizarLista();
        }
    }

    /**
     * Ejemplo de cómo usar el service para cobrar
     */
    public function cobrar()
    {
        $response = $this->ventaService->cobrarVenta(
            $this->cuenta,
            $this->metodosSeleccionados,
            $this->totalAcumuladoMetodos,
            $this->calcularSubtotalConDescuento(),
            $this->descuentoSaldo
        );

        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->cuenta = $response->data;
        }
    }

    /**
     * Ejemplo de cómo usar el service para eliminar un producto
     */
    public function eliminarProducto(Producto $producto)
    {
        $response = $this->productoVentaService->eliminarProductoCompleto($this->cuenta, $producto);
        
        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->actualizarLista();
        }
    }

    /**
     * Ejemplo de cómo agregar un adicional
     */
    public function agregarAdicional(Adicionale $adicional, $item)
    {
        $response = $this->productoVentaService->agregarAdicional(
            $this->cuenta, 
            $this->productoapuntado, 
            $adicional, 
            $item
        );
        
        $this->dispatchBrowserEvent('alert', [
            'type' => $response->type,
            'message' => $response->message,
        ]);

        if ($response->success) {
            $this->actualizarLista();
        }
    }

    /**
     * Actualiza la lista usando el calculadora service
     */
    private function actualizarLista()
    {
        $calculos = $this->calculadoraService->calcularVenta($this->cuenta, $this->descuentoSaldo);
        
        // Actualizar propiedades del componente
        $this->listacuenta = $calculos->listaCuenta;
        $this->subtotal = $calculos->subtotal;
        $this->itemsCuenta = $calculos->itemsCuenta;
        $this->descuentoProductos = $calculos->descuentoProductos;
        $this->subtotalConDescuento = $calculos->subtotalConDescuento;
        
        $this->cuenta->puntos = $calculos->puntos;
    }

    /**
     * Método helper para cálculos
     */
    private function calcularSubtotalConDescuento(): float
    {
        $calculos = $this->calculadoraService->calcularVenta($this->cuenta, $this->descuentoSaldo);
        return $calculos->subtotalConDescuento;
    }

    public function render()
    {
        // Tu lógica de render existente
        return view('livewire.admin.ventas.ventas-index-refactored')
            ->extends('admin.master')
            ->section('content');
    }
}
