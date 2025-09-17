<?php

namespace App\Http\Livewire\Admin\Caja;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Helpers\CajaReporteHelper;
use Illuminate\Support\Facades\DB;

class ReporteMensual extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $mesSeleccionado;
    public $anioSeleccionado;
    public $mostrarDetalles = false;
    protected $mesesDisponibles;

    // Propiedades para paginación
    public $mesesPorPagina = 6;

    // Propiedad para búsqueda
    public $search = '';

    public function mount()
    {
        // Generar los meses disponibles automáticamente
        $this->generarMesesDisponibles();
    }

    public function updatedMesesPorPagina()
    {
        $this->generarMesesDisponibles();
    }

    public function updatedPage()
    {
        $this->generarMesesDisponibles();
    }

    public function updatedSearch()
    {
        $this->resetPage(); // Resetear a la primera página cuando se busca
        $this->generarMesesDisponibles();
    }

    /**
     * Genera los meses disponibles basándose en los registros reales de la base de datos
     */
    private function generarMesesDisponibles()
    {
        // Consultar todos los meses y años que tienen registros de ventas
        $query = DB::table('historial_ventas')
            ->select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as anio')
            )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc');

        // Aplicar filtro de búsqueda si existe
        if (!empty($this->search)) {
            $searchTerm = trim($this->search);

            // Buscar por año
            if (is_numeric($searchTerm)) {
                $query->where(DB::raw('YEAR(created_at)'), '=', $searchTerm);
            } else {
                // Buscar por nombre de mes (en español)
                $mesNumero = $this->buscarMesPorNombre($searchTerm);
                if ($mesNumero) {
                    $query->where(DB::raw('MONTH(created_at)'), '=', $mesNumero);
                } else {
                    // Si no encuentra el mes, buscar por año que contenga el término
                    $query->where(DB::raw('YEAR(created_at)'), 'LIKE', '%' . $searchTerm . '%');
                }
            }
        }

        // Aplicar paginación
        $mesesConRegistros = $query->paginate($this->mesesPorPagina);

        $mesesFormateados = collect();

        foreach ($mesesConRegistros as $registro) {
            $fecha = Carbon::create($registro->anio, $registro->mes, 1);
            $mesesFormateados->push([
                'mes' => $registro->mes,
                'anio' => $registro->anio,
                'nombre' => $this->obtenerNombreMes($registro->mes),
                'nombre_completo' => $this->obtenerNombreMes($registro->mes) . ' ' . $registro->anio,
                'fecha' => $fecha
            ]);
        }

        // Crear un LengthAwarePaginator personalizado con los datos formateados
        $this->mesesDisponibles = new \Illuminate\Pagination\LengthAwarePaginator(
            $mesesFormateados,
            $mesesConRegistros->total(),
            $this->mesesPorPagina,
            $this->page, // Usar la página actual de Livewire
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
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
        // Regenerar los meses disponibles para asegurar que estén disponibles
        $this->generarMesesDisponibles();
    }

    /**
     * Cambia la cantidad de meses por página
     */
    public function cambiarMesesPorPagina($cantidad)
    {
        $this->mesesPorPagina = $cantidad;
        $this->generarMesesDisponibles();
    }

    /**
     * Obtiene los meses disponibles para la vista
     */
    public function getMesesDisponiblesProperty()
    {
        // Siempre regenerar para asegurar que esté actualizado
        $this->generarMesesDisponibles();
        return $this->mesesDisponibles;
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
    public function getCategoriasMasVendidas($mes = null, $anio = null)
    {
        $mesSeleccionado = null;
        $anioSeleccionado = null;
        if (isset($mes) && isset($anio)) {
            $mesSeleccionado = $mes;
            $anioSeleccionado = $anio;
        } else {
            $mesSeleccionado = $this->mesSeleccionado;
            $anioSeleccionado = $this->anioSeleccionado;
        }
        return CajaReporteHelper::categoriasMasVendidasMensual($mesSeleccionado, $anioSeleccionado);
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
            'monto_total',
            'bar'
        );
    }

    /**
     * Genera la URL del gráfico de categorías
     */
    public function getGraficoCategorias($mes = null, $anio = null)
    {
        $mesSeleccionado = null;
        $anioSeleccionado = null;
        $tipoGrafico = 'bar';
        if (isset($mes) && isset($anio)) {
            $mesSeleccionado = $mes;
            $anioSeleccionado = $anio;
            $tipoGrafico = 'doughnut';
        } else {
            $mesSeleccionado = $this->mesSeleccionado;
            $anioSeleccionado = $this->anioSeleccionado;
        }
        $categorias = $this->getCategoriasMasVendidas($mesSeleccionado, $anioSeleccionado);
        return \App\Helpers\GraficosHelper::crearGrafico(
            $categorias,
            'Categorías Más Vendidas - ' . $this->obtenerNombreMes($mesSeleccionado) . ' ' . $anioSeleccionado,
            true,
            'nombre_categoria',
            'monto_total',
            $tipoGrafico
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
        return count(CajaReporteHelper::ClientesActivosMensual($mes, $anio));
    }

    /**
     * Obtiene el total de productos vendidos para un mes específico
     */
    public function getTotalProductosVendidos($mes, $anio)
    {
        return count(CajaReporteHelper::ProductosActivosMensual($mes, $anio));
    }



    /**
     * Obtiene el total de cantidad de productos vendidos para un mes específico
     */
    public function getTotalCantidadProductosVendidos($mes, $anio)
    {
        $productos = CajaReporteHelper::CantidadProductosActivosMensual($mes, $anio);
        $totalCantidad = 0;
        
        foreach ($productos as $producto) {
            $totalCantidad += $producto->cantidad_total;
        }
        
        return $totalCantidad;
    }




    /**
     * Obtiene el total de categorias vendidas para un mes específico
     */
    public function getTotalCategoriasVendidas($mes, $anio)
    {
        return count(CajaReporteHelper::CategoriasActivasMensual($mes, $anio));
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
     * Busca el número del mes por su nombre en español
     */
    private function buscarMesPorNombre(string $nombreMes): ?int
    {
        $meses = [
            'enero' => 1,
            'febrero' => 2,
            'marzo' => 3,
            'abril' => 4,
            'mayo' => 5,
            'junio' => 6,
            'julio' => 7,
            'agosto' => 8,
            'septiembre' => 9,
            'octubre' => 10,
            'noviembre' => 11,
            'diciembre' => 12,
            // También aceptar abreviaciones comunes
            'ene' => 1,
            'feb' => 2,
            'mar' => 3,
            'abr' => 4,
            'may' => 5,
            'jun' => 6,
            'jul' => 7,
            'ago' => 8,
            'sep' => 9,
            'oct' => 10,
            'nov' => 11,
            'dic' => 12
        ];

        $nombreMes = strtolower(trim($nombreMes));

        return $meses[$nombreMes] ?? null;
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

    public function getTotalSubcategoriasPorCategoria($id, $mes, $anio)
    {
        return CajaReporteHelper::totalSubcategoriasPorCategoria($id, $mes, $anio);
    }

    public function render()
    {
        return view('livewire.admin.caja.reporte-mensual', [
            'mesesDisponibles' => $this->mesesDisponibles
        ])
            ->extends('admin.master')
            ->section('content');
    }
}
