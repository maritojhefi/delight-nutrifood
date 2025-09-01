<?php

namespace App\Helpers;

use Mike42\Escpos\Printer;
use Illuminate\Support\Str;
use App\Models\ReciboImpreso;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Facades\DB;
use Rawilk\Printing\Facades\Printing;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;




class CajaReporteHelper
{
    public static function graficoIngresosPorCajero($coleccion)
    {
        return GraficosHelper::crearGrafico($coleccion, 'Composición de ingresos', true, 'nombre', 'monto');
    }
    public static function graficoComposicionIngresos($totalIngresos, $ingresoVentasPOS, $totalSaldosPagados): string
    {
        return GraficosHelper::crearGrafico(['VENTAS' => $ingresoVentasPOS, 'SALDOS' => $totalSaldosPagados], 'Composición de ingresos');
    }
    public static function arrayProductosVendidosRanking(int $cajaId, ?int $cajeroId = null): array
    {
        // Iniciar la consulta
        $query = DB::table('historial_ventas')
            ->join('historial_venta_producto', 'historial_ventas.id', '=', 'historial_venta_producto.historial_venta_id')
            ->join('productos', 'historial_venta_producto.producto_id', '=', 'productos.id')
            ->select(
                'productos.id',
                'productos.nombre',
                DB::raw('SUM(historial_venta_producto.cantidad) as cantidad_total'),
                DB::raw('ROUND(SUM(historial_venta_producto.precio_subtotal), 2) as suma_total')
            )
            ->where('historial_ventas.caja_id', $cajaId);

        // Filtrar por cajero si se proporciona
        if (!is_null($cajeroId)) {
            $query->where('historial_ventas.usuario_id', $cajeroId);
        }

        // Agrupar y ordenar los resultados
        $productos = $query
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('cantidad_total')
            ->get();

        // Convertir a array
        return $productos->toArray();
    }
    public static function urlGraficoProductosVendidos($arrayProductosVendidos): string
    {
        return GraficosHelper::crearGrafico($arrayProductosVendidos, 'Productos mas vendidos', false, 'nombre', 'cantidad_total');
    }

    public static function graficoIngresosPorMetodo($ingresosPorMetodoPago)
    {
        return GraficosHelper::crearGrafico($ingresosPorMetodoPago);
    }



























    /**
     * Obtiene un array con las ventas agrupadas por categoría para una caja específica
     * 
     * @param int $cajaId - ID de la caja
     * @param int|null $cajeroId - ID del cajero (opcional, para filtrar por cajero)
     * @return array - Array con datos de ventas por categoría
     */
    public static function arrayVentasPorCategoria(int $cajaId, ?int $cajeroId = null): array
    {
        // Iniciar la consulta principal que une todas las tablas necesarias
        $query = DB::table('historial_ventas')
            ->join('historial_venta_producto', 'historial_ventas.id', '=', 'historial_venta_producto.historial_venta_id')
            ->join('productos', 'historial_venta_producto.producto_id', '=', 'productos.id')
            ->join('subcategorias', 'productos.subcategoria_id', '=', 'subcategorias.id')
            ->join('categorias', 'subcategorias.categoria_id', '=', 'categorias.id')
            ->select(
                'categorias.id',
                'categorias.nombre as nombre_categoria',
                DB::raw('ROUND(SUM(historial_venta_producto.precio_subtotal), 2) as suma_total'),
                DB::raw('SUM(historial_venta_producto.cantidad) as cantidad_total')

            )
            ->where('historial_ventas.caja_id', $cajaId);

        // Filtrar por cajero si se proporciona (opcional)
        if (!is_null($cajeroId)) {
            $query->where('historial_ventas.usuario_id', $cajeroId);
        }

        // Agrupar por categoría y ordenar por cantidad total descendente
        $categorias = $query
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderByDesc('suma_total')
            ->get();
        // dd($categorias);
        // Convertir a array y retornar
        return $categorias->toArray();
    }

    /**
     * Genera la URL del gráfico de ventas por categoría
     * 
     * @param array $arrayVentasPorCategoria - Array con datos de ventas por categoría
     * @return string - URL del gráfico generado por QuickChart
     */
    public static function urlGraficoVentasPorCategoria($arrayVentasPorCategoria): string
    {
        // Utiliza el GraficosHelper para crear el gráfico con los datos de categorías
        // 'nombre_categoria' es el campo que contiene el nombre de la categoría
        // 'cantidad_total' es el campo que contiene la cantidad de productos vendidos
        return GraficosHelper::crearGrafico(
            $arrayVentasPorCategoria,
            'Ventas por Categoría',
            true,
            'nombre_categoria',
            'suma_total',
            'bar'
        );
    }

    // ========================================
    // NUEVAS FUNCIONES PARA REPORTES MENSUALES
    // ========================================

    /**
     * Obtiene el top 10 de clientes que más compraron en un mes específico
     * 
     * @param int $mes - Mes (1-12)
     * @param int $anio - Año
     * @return array - Array con top 10 clientes
     */
    public static function top10ClientesMensual(int $mes, int $anio): array
    {
        $query = DB::table('historial_ventas')
            ->join('users', 'historial_ventas.cliente_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name as nombre_cliente',
                DB::raw('COUNT(historial_ventas.id) as total_compras'),
                DB::raw('ROUND(SUM(historial_ventas.total_pagado), 2) as monto_total')
            )
            ->whereNotNull('historial_ventas.cliente_id')
            ->whereMonth('historial_ventas.created_at', $mes)
            ->whereYear('historial_ventas.created_at', $anio)
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('monto_total')
            ->limit(10)
            ->get();

        return $query->toArray();
    }

    /**
     * Obtiene los métodos de pago más usados en un mes específico
     * 
     * @param int $mes - Mes (1-12)
     * @param int $anio - Año
     * @return array - Array con métodos de pago y su uso
     */
    public static function metodosPagoMasUsadosMensual(int $mes, int $anio): array
    {
        $query = DB::table('historial_ventas')
            ->join('historial_venta_metodo_pago', 'historial_ventas.id', '=', 'historial_venta_metodo_pago.historial_venta_id')
            ->join('metodos_pagos', 'historial_venta_metodo_pago.metodo_pago_id', '=', 'metodos_pagos.id')
            ->select(
                'metodos_pagos.id',
                'metodos_pagos.nombre_metodo_pago',
                DB::raw('COUNT(historial_ventas.id) as total_ventas'),
                DB::raw('ROUND(SUM(historial_venta_metodo_pago.monto), 2) as monto_total')
            )
            ->whereMonth('historial_ventas.created_at', $mes)
            ->whereYear('historial_ventas.created_at', $anio)
            ->groupBy('metodos_pagos.id', 'metodos_pagos.nombre_metodo_pago')
            ->orderByDesc('monto_total')
            ->get();

        return $query->toArray();
    }

    /**
     * Obtiene el top 10 de productos más vendidos en un mes específico
     * 
     * @param int $mes - Mes (1-12)
     * @param int $anio - Año
     * @return array - Array con top 10 productos
     */
    public static function top10ProductosMensual(int $mes, int $anio): array
    {
        $query = DB::table('historial_ventas')
            ->join('historial_venta_producto', 'historial_ventas.id', '=', 'historial_venta_producto.historial_venta_id')
            ->join('productos', 'historial_venta_producto.producto_id', '=', 'productos.id')
            ->select(
                'productos.id',
                'productos.nombre',
                DB::raw('SUM(historial_venta_producto.cantidad) as cantidad_total'),
                DB::raw('ROUND(SUM(
                    CASE 
                        WHEN historial_ventas.subtotal > 0 
                        THEN (historial_venta_producto.precio_subtotal / historial_ventas.subtotal) * historial_ventas.total_pagado
                        ELSE historial_venta_producto.precio_subtotal
                    END
                ), 2) as monto_total')
            )
            ->whereMonth('historial_ventas.created_at', $mes)
            ->whereYear('historial_ventas.created_at', $anio)
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('monto_total')
            ->limit(10)
            ->get();

        return $query->toArray();
    }

    /**
     * Obtiene las categorías más vendidas en un mes específico
     * 
     * @param int $mes - Mes (1-12)
     * @param int $anio - Año
     * @return array - Array con categorías y sus ventas
     */
    public static function categoriasMasVendidasMensual(int $mes, int $anio): array
    {
        $query = DB::table('historial_ventas')
            ->join('historial_venta_producto', 'historial_ventas.id', '=', 'historial_venta_producto.historial_venta_id')
            ->join('productos', 'historial_venta_producto.producto_id', '=', 'productos.id')
            ->join('subcategorias', 'productos.subcategoria_id', '=', 'subcategorias.id')
            ->join('categorias', 'subcategorias.categoria_id', '=', 'categorias.id')
            ->select(
                'categorias.id',
                'categorias.nombre as nombre_categoria',
                DB::raw('SUM(historial_venta_producto.cantidad) as cantidad_total'),
                DB::raw('ROUND(SUM(
                    CASE 
                        WHEN historial_ventas.subtotal > 0 
                        THEN (historial_venta_producto.precio_subtotal / historial_ventas.subtotal) * historial_ventas.total_pagado
                        ELSE historial_venta_producto.precio_subtotal
                    END
                ), 2) as monto_total')
            )
            ->whereMonth('historial_ventas.created_at', $mes)
            ->whereYear('historial_ventas.created_at', $anio)
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderByDesc('monto_total')
            ->get();

        return $query->toArray();
    }

    /**
     * Obtiene la comparativa de ventas del mes actual vs los 2 meses anteriores
     * 
     * @param int $mes - Mes actual (1-12)
     * @param int $anio - Año actual
     * @return array - Array con comparativa de meses
     */
    public static function comparativaMeses(int $mes, int $anio): array
    {
        $meses = [];
        
        // Mes actual
        $meses[] = [
            'mes' => $mes,
            'anio' => $anio,
            'nombre' => self::obtenerNombreMes($mes),
            'monto' => self::obtenerTotalVentasMensual($mes, $anio)
        ];
        
        // Mes anterior
        $mesAnterior = $mes - 1;
        $anioAnterior = $anio;
        if ($mesAnterior < 1) {
            $mesAnterior = 12;
            $anioAnterior = $anio - 1;
        }
        
        $meses[] = [
            'mes' => $mesAnterior,
            'anio' => $anioAnterior,
            'nombre' => self::obtenerNombreMes($mesAnterior),
            'monto' => self::obtenerTotalVentasMensual($mesAnterior, $anioAnterior)
        ];
        
        // Mes anterior al anterior
        $mesAnterior2 = $mesAnterior - 1;
        $anioAnterior2 = $anioAnterior;
        if ($mesAnterior2 < 1) {
            $mesAnterior2 = 12;
            $anioAnterior2 = $anioAnterior - 1;
        }
        
        $meses[] = [
            'mes' => $mesAnterior2,
            'anio' => $anioAnterior2,
            'nombre' => self::obtenerNombreMes($mesAnterior2),
            'monto' => self::obtenerTotalVentasMensual($mesAnterior2, $anioAnterior2)
        ];
        
        return $meses;
    }

    /**
     * Obtiene el total de ventas para un mes específico
     * 
     * @param int $mes - Mes (1-12)
     * @param int $anio - Año
     * @return float - Total de ventas del mes
     */
    public static function obtenerTotalVentasMensual(int $mes, int $anio): float
    {
        $total = DB::table('historial_ventas')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->sum('total_pagado');
            
        return round(floatval($total), 2);
    }

    /**
     * Obtiene el nombre del mes en español
     * 
     * @param int $mes - Número del mes (1-12)
     * @return string - Nombre del mes en español
     */
    public static function obtenerNombreMes(int $mes): string
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        return $meses[$mes] ?? 'Desconocido';
    }
}
