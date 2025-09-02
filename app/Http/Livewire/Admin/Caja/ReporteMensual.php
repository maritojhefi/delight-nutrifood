<?php

namespace App\Http\Livewire\Admin\Caja;

use Carbon\Carbon;
use Livewire\Component;
use App\Helpers\CajaReporteHelper;

class ReporteMensual extends Component
{
    public $mesSeleccionado;
    public $anioSeleccionado;
    public $mostrarDetalles = false;
    public $mesesDisponibles = [];

    public function mount()
    {
        // Generar los meses disponibles automáticamente
        $this->generarMesesDisponibles();
    }

    /**
     * Genera los meses disponibles automáticamente (últimos 6 meses)
     */
    private function generarMesesDisponibles()
    {
        $meses = [];
        $fechaActual = now();

        // Generar los últimos 6 meses
        for ($i = 0; $i < 6; $i++) {
            $fecha = $fechaActual->copy()->subMonths($i);
            $meses[] = [
                'mes' => $fecha->month,
                'anio' => $fecha->year,
                'nombre' => $this->obtenerNombreMes($fecha->month),
                'nombre_completo' => $this->obtenerNombreMes($fecha->month) . ' ' . $fecha->year,
                'fecha' => $fecha
            ];
        }

        $this->mesesDisponibles = $meses;
    }

    /**
     * Muestra la vista de detalles con gráficos para un mes específico
     */
    public function mostrarDetalles($mes, $anio)
    {
        $this->mesSeleccionado = intval($mes);
        $this->anioSeleccionado = intval($anio);
        $this->mostrarDetalles = true;
    }

    /**
     * Regresa a la vista inicial de cards
     */
    public function volverALista()
    {
        $this->mostrarDetalles = false;
        $this->reset(['mesSeleccionado', 'anioSeleccionado']);
    }

    /**
     * Obtiene el top 10 de clientes del mes seleccionado
     */
    public function getTop10Clientes()
    {
        return CajaReporteHelper::top10ClientesMensual($this->mesSeleccionado, $this->anioSeleccionado);
    }

    /**
     * Obtiene los métodos de pago más usados del mes seleccionado
     */
    public function getMetodosPagoMasUsados()
    {
        return CajaReporteHelper::metodosPagoMasUsadosMensual($this->mesSeleccionado, $this->anioSeleccionado);
    }

    /**
     * Obtiene el top 10 de productos del mes seleccionado
     */
    public function getTop10Productos()
    {
        return CajaReporteHelper::top10ProductosMensual($this->mesSeleccionado, $this->anioSeleccionado);
    }

    /**
     * Obtiene las categorías más vendidas del mes seleccionado
     */
    public function getCategoriasMasVendidas()
    {
        return CajaReporteHelper::categoriasMasVendidasMensual($this->mesSeleccionado, $this->anioSeleccionado);
    }

    /**
     * Obtiene la comparativa de meses
     */
    public function getComparativaMeses()
    {
        return CajaReporteHelper::comparativaMeses($this->mesSeleccionado, $this->anioSeleccionado);
    }

    /**
     * Genera la URL del gráfico de top 10 clientes
     */
    public function getGraficoTop10Clientes()
    {
        $clientes = $this->getTop10Clientes();
        return \App\Helpers\GraficosHelper::crearGrafico(
            $clientes,
            'Top 10 Clientes - ' . $this->obtenerNombreMes($this->mesSeleccionado) . ' ' . $this->anioSeleccionado,
            true,
            'nombre_cliente',
            'monto_total',
            'bar'
        );
    }

    /**
     * Genera la URL del gráfico de métodos de pago
     */
    public function getGraficoMetodosPago()
    {
        $metodos = $this->getMetodosPagoMasUsados();
        return \App\Helpers\GraficosHelper::crearGrafico(
            $metodos,
            'Métodos de Pago - ' . $this->obtenerNombreMes($this->mesSeleccionado) . ' ' . $this->anioSeleccionado,
            true,
            'nombre_metodo_pago',
            'monto_total',
            'doughnut'
        );
    }

    /**
     * Genera la URL del gráfico de top 10 productos
     */
    public function getGraficoTop10Productos()
    {
        $productos = $this->getTop10Productos();
        return \App\Helpers\GraficosHelper::crearGrafico(
            $productos,
            'Top 10 Productos - ' . $this->obtenerNombreMes($this->mesSeleccionado) . ' ' . $this->anioSeleccionado,
            true,
            'nombre',
            'cantidad_total',
            'bar'
        );
    }

    /**
     * Genera la URL del gráfico de categorías
     */
    public function getGraficoCategorias()
    {
        $categorias = $this->getCategoriasMasVendidas();
        return \App\Helpers\GraficosHelper::crearGrafico(
            $categorias,
            'Categorías Más Vendidas - ' . $this->obtenerNombreMes($this->mesSeleccionado) . ' ' . $this->anioSeleccionado,
            true,
            'nombre_categoria',
            'monto_total',
            'bar'
        );
    }

    /**
     * Genera la URL del gráfico de comparativa de meses
     */
    public function getGraficoComparativaMeses()
    {
        $meses = $this->getComparativaMeses();
        return \App\Helpers\GraficosHelper::crearGrafico(
            $meses,
            'Comparativa de Ventas - Últimos 3 Meses',
            true,
            'nombre',
            'monto',
            'bar'
        );
    }

    /**
     * Obtiene el total de ventas para un mes específico
     */
    public function getTotalVentasMes($mes, $anio)
    {
        return CajaReporteHelper::obtenerTotalVentasMensual($mes, $anio);
    }

    /**
     * Obtiene el total de clientes activos para un mes específico
     */
    public function getTotalClientesActivos($mes, $anio)
    {
        return count(CajaReporteHelper::top10ClientesMensual($mes, $anio));
    }

    /**
     * Obtiene el total de productos vendidos para un mes específico
     */
    public function getTotalProductosVendidos($mes, $anio)
    {
        return count(CajaReporteHelper::top10ProductosMensual($mes, $anio));
    }

    /**
     * Genera la URL del mini-gráfico para un mes específico
     */
    public function getMiniGraficoMes($mes, $anio)
    {
        $clientes = CajaReporteHelper::top10ClientesMensual($mes, $anio);
        return \App\Helpers\GraficosHelper::crearGrafico(
            $clientes,
            $this->obtenerNombreMes($mes) . ' ' . $anio,
            true,
            'nombre_cliente',
            'monto_total',
            'doughnut'
        );
    }

    /**
     * Obtiene el nombre del mes en español
     */
    private function obtenerNombreMes(int $mes): string
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        return $meses[$mes] ?? 'Desconocido';
    }

    /**
     * MÉTODO DE DEPURACIÓN: Verifica que los datos se estén obteniendo correctamente
     */
    public function debugDatos()
    {
        $debug = [
            'mes_seleccionado' => $this->mesSeleccionado,
            'anio_seleccionado' => $this->anioSeleccionado,
            'top10_clientes_count' => count($this->getTop10Clientes()),
            'metodos_pago_count' => count($this->getMetodosPagoMasUsados()),
            'top10_productos_count' => count($this->getTop10Productos()),
            'categorias_count' => count($this->getCategoriasMasVendidas()),
            'comparativa_meses_count' => count($this->getComparativaMeses()),
        ];

        // Mostrar en consola del navegador
        $this->dispatchBrowserEvent('console.log', ['data' => $debug]);

        return $debug;
    }

    public function render()
    {
        return view('livewire.admin.caja.reporte-mensual')
            ->extends('admin.master')
            ->section('content');
    }
}
