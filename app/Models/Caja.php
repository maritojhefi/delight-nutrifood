<?php

namespace App\Models;

use App\Models\Sucursale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = [
        'acumulado',
        'entrada',
        'sucursale_id',
        'estado'

    ];
    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function ventas()
    {
        return $this->hasMany(Historial_venta::class);
    }
    public function saldos()
    {
        return $this->hasMany(Saldo::class, 'caja_id');
    }
    public function saldosPagadosSinVenta()
    {
        return $this->hasMany(Saldo::class, 'caja_id')->where('historial_ventas_id', null)->where('es_deuda', false)->where('anulado', false);
    }
    private function calcularIngresosPorMetodoPago($coleccion): array
    {
        $acumuladoPorMetodoPago = [];

        foreach ($coleccion as $item) {
            foreach ($item->metodosPagos as $metodo) {
                $monto = $metodo->pivot->monto;
                $nombre = $metodo->nombre_metodo_pago;

                if (isset($acumuladoPorMetodoPago[$nombre])) {
                    $acumuladoPorMetodoPago[$nombre] += $monto;
                } else {
                    $acumuladoPorMetodoPago[$nombre] = $monto;
                }
            }
        }

        return $acumuladoPorMetodoPago;
    }
    public function ingresosPorMetodoPagoDeVentas(): array
    {
        return $this->calcularIngresosPorMetodoPago($this->ventas);
    }

    public function ingresosPorMetodoPagoDeSaldos(): array
    {
        return $this->calcularIngresosPorMetodoPago($this->saldosPagadosSinVenta);
    }
    public function ingresosTotalesPorMetodoPago(): array
    {
        $ventas = $this->ingresosPorMetodoPagoDeVentas();
        $saldos = $this->ingresosPorMetodoPagoDeSaldos();

        return array_merge_recursive(
            array_map('floatval', $ventas),
            array_map('floatval', $saldos)
        );
    }


    public function totalDescuentos(): float
    {
        return floatval($this->ventas()->sum('total_descuento'));
    }

    public function ingresoVentasPOS(): float
    {
        return floatval($this->ventas()->sum('total_pagado'));
    }
    public function totalIngresoAbsoluto(): float
    {
        return floatval($this->ingresoVentasPOS() + $this->totalSaldosPagadosSinVenta());
    }
    public function totalSaldoExcedentes(): float
    {
        return floatval($this->ventas()->where('a_favor_cliente', true)->sum('saldo_monto'));
    }

    public function totalPuntos(): float
    {
        return floatval($this->ventas()->sum('puntos'));
    }

    public function totalSaldosPagadosSinVenta(): float
    {
        return floatval($this->saldosPagadosSinVenta()->sum('monto'));
    }
    public function generarGraficoIngresosPorMetodoPago(): string
    {
        // Obtener datos desde el modelo (supongamos que la función ya existe)
        $ingresosPorMetodoPago = $this->ingresosTotalesPorMetodoPago();

        // Formatear los datos para el gráfico
        $labels = array_keys($ingresosPorMetodoPago);
        $data = array_values($ingresosPorMetodoPago);

        // Colores para el gráfico
        $colores = [
            'rgb(255, 99, 132)',  // Red
            'rgb(255, 159, 64)',  // Orange
            'rgb(255, 205, 86)',  // Yellow
            'rgb(75, 192, 192)',  // Green
            'rgb(54, 162, 235)',  // Blue
            'rgb(153, 102, 255)', // Purple
            'rgb(201, 203, 207)', // Grey
        ];

        // Armar el JSON para el gráfico
        $chartConfig = [
            'type' => 'doughnut',
            'data' => [
                'datasets' => [[
                    'data' => $data,
                    'backgroundColor' => array_slice($colores, 0, count($data)),
                    'label' => 'Ingresos por Método de Pago',
                ]],
                'labels' => $labels,
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Distribución de Ingresos por Métodos de Pago',
                ],
            ],
        ];

        // Convertir el array del gráfico a JSON
        $chartJson = json_encode($chartConfig);

        // Armar la URL para QuickChart
        $baseUrl = 'https://quickchart.io/chart?c=';
        return $baseUrl . urlencode($chartJson);
    }
    public function urlGraficoComposicionIngresos(): string
    {
        // Calcular datos
        $totalIngresos = $this->totalIngresoAbsoluto();
        $ingresoVentasPOS = $this->ingresoVentasPOS();
        $totalSaldosPagados = $this->totalSaldosPagadosSinVenta();

        // Evitar división por cero
        if ($totalIngresos == 0 || $ingresoVentasPOS == 0 ||  $totalSaldosPagados == 0) {
            $datos = [100];
            $etiquetas = ['Sin datos'];
        } else {
            // Calcular porcentajes
            $porcentajeVentasPOS = ($ingresoVentasPOS / $totalIngresos) * 100;
            $porcentajeSaldosPagados = ($totalSaldosPagados / $totalIngresos) * 100;

            $datos = [$ingresoVentasPOS, $totalSaldosPagados];
            $etiquetas = ['Ventas', 'Saldos/Excedentes'];
        }

        // Construir configuración del gráfico
        $config = [
            'type' => 'doughnut',
            'data' => [
                'datasets' => [
                    [
                        'data' => $datos,
                        'backgroundColor' => [
                            '#3A82EF', // Color para Ventas POS
                            '#FB3E7A', // Color para Saldos Pagados
                        ],
                        'label' => 'Composición de Ingresos',
                    ],
                ],
                'labels' => $etiquetas,
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Composición del Total de Ingresos',
                ],
            ],
        ];

        // Codificar en JSON y generar URL
        $jsonConfig = json_encode($config);
        return "https://quickchart.io/chart?c=" . urlencode($jsonConfig);
    }
}
