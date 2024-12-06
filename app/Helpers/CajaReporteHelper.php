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
                DB::raw('SUM(historial_venta_producto.precio_subtotal) as suma_total')
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
}
