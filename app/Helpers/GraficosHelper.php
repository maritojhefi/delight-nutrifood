<?php

namespace App\Helpers;

use App\Models\Historial_venta;
use App\Models\Plane;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;


class GraficosHelper
{
    public static function obtenerColorPosicion($posicion)
    {
        // Colores predefinidos en un array
        $colors = [
            'rgb(75, 192, 192)',  // Verde claro
            'rgb(255, 99, 132)',  // Rojo
            'rgb(255, 205, 86)',  // Amarillo
            'rgb(54, 162, 235)',  // Azul
            'rgb(153, 102, 255)', // Morado
            'rgb(201, 203, 207)', // Gris claro
        ];

        // Determina el índice cíclico basado en la posición
        $index = $posicion % count($colors);

        return $colors[$index];
    }
    public static function crearGrafico($coleccionData, $titulo = null, $mostrarLabels = true, $variableLabel = null, $variableData = null, $tipoGrafico = 'doughnut'): string
    {
        if ($variableLabel == null && $variableData == null) {
            $labels = array_keys($coleccionData); // Extrae los nombres de los productos
            $data = array_values($coleccionData); // Extrae las cantidades totales
        } else {
            $labels = array_column($coleccionData, $variableLabel); // Extrae los nombres de los productos
            $data = array_column($coleccionData, $variableData); // Extrae las cantidades totales
        }

        $labels = array_map(function ($label) {
            return mb_strimwidth($label, 0, 15, '...'); // Limita a 15 caracteres y añade "..." si es más largo
        }, $labels); // Extrae los nombres de los productos y los limita

        $coloresItems = array_map(function ($index) {
            return self::obtenerColorPosicion($index);
        }, array_keys($labels));
        // Configuración del gráfico
        $chartConfig = [
            'type' => $tipoGrafico,
            'data' => [
                'labels' => $mostrarLabels ? $labels : '',
                'datasets' => [
                    [
                        'label' => 'Cantidad Vendida',
                        'data' => $data,
                        'backgroundColor' => $coloresItems,
                    ],
                ],
            ],
            'options' => [
                'title' => [
                    'display' => $titulo ? true : false,
                    'text' => $titulo,
                ],
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Productos Vendidos',
                    ],

                    'datalabels' => [
                        'color' => 'white', // Texto blanco
                        'font' => [
                            'size' => 16, // Tamaño del texto
                            'weight' => 'bold', // Negrita
                        ],
                        'anchor' => 'center', // Posiciona el texto al centro de la barra
                        'align' => 'center', // Alineación al centro
                    ],
                    'scales' => [
                        'x' => [
                            'ticks' => [
                                'color' => '#000', // Opcional: color del texto en el eje X
                            ],
                        ],
                        'y' => [
                            'ticks' => [
                                'color' => '#000', // Opcional: color del texto en el eje Y
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // Generar la URL del gráfico con QuickChart
        $baseUrl = 'https://quickchart.io/chart';
        $chartUrl = $baseUrl . '?c=' . urlencode(json_encode($chartConfig));

        return $chartUrl;
    }
}
