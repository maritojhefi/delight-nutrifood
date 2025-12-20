<?php

namespace App\Services\Ventas;

use Carbon\Carbon;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\AreaDespacho;
use App\Models\AreaDespachoTicket;
use App\Helpers\CustomPrint;
use Illuminate\Support\Facades\DB;
use App\Services\Ventas\DTOs\VentaResponse;
use App\Services\Ventas\Contracts\ComandaServiceInterface;

class ComandaService implements ComandaServiceInterface
{
    /**
     * Obtiene los items de una venta agrupados por área de despacho
     * diferenciando entre items con y sin nro_ticket
     */
    public function obtenerItemsAgrupadosPorArea(Venta $venta): VentaResponse
    {
        try {
            $productos = $venta->productos()
                ->with(['areaDespacho'])
                ->get();

            $itemsAgrupados = [];

            foreach ($productos as $producto) {
                // Solo procesar productos que tengan área de despacho
                if (!$producto->seccion || !$producto->areaDespacho) {
                    continue;
                }

                $areaDespacho = $producto->areaDespacho;
                $codigoArea = $areaDespacho->codigo_area;
                $nombreArea = $areaDespacho->nombre_area;

                // Inicializar estructura si no existe
                if (!isset($itemsAgrupados[$codigoArea])) {
                    $itemsAgrupados[$codigoArea] = [
                        'area' => [
                            'id' => $areaDespacho->id,
                            'codigo' => $codigoArea,
                            'nombre' => $nombreArea,
                            'id_impresora' => $areaDespacho->id_impresora,
                        ],
                        'con_ticket' => [], // Agrupado por ticket
                        'sin_ticket' => [] // Array simple de items sin ticket
                    ];
                }

                // Obtener datos del pivot
                $pivot = DB::table('producto_venta')
                    ->where('producto_id', $producto->id)
                    ->where('venta_id', $venta->id)
                    ->first();

                if (!$pivot) {
                    continue;
                }

                $nroTickets = json_decode($pivot->nro_ticket ?? '{}', true);
                $adicionales = json_decode($pivot->adicionales ?? '{}', true);
                $cantidad = $pivot->cantidad;

                // Procesar cada item del producto
                for ($i = 1; $i <= $cantidad; $i++) {
                    $itemData = [
                        'producto_venta_id' => $pivot->id,
                        'producto_id' => $producto->id,
                        'item_index' => $i,
                        'nombre' => $producto->nombre,
                        'cantidad' => 1,
                        'observacion' => $pivot->observacion,
                        'adicionales' => $adicionales[$i]['adicionales'] ?? [],
                        'precio' => $producto->precioReal(),
                    ];

                    // Verificar si tiene ticket
                    if (isset($nroTickets[$i]) && !empty($nroTickets[$i])) {
                        $nroTicket = $nroTickets[$i]['nro_ticket'] ?? null;
                        $fechaImpresion = $nroTickets[$i]['fecha_impresion'] ?? null;

                        // Agrupar por ticket
                        if (!isset($itemsAgrupados[$codigoArea]['con_ticket'][$nroTicket])) {
                            $itemsAgrupados[$codigoArea]['con_ticket'][$nroTicket] = [
                                'nro_ticket' => $nroTicket,
                                'fecha_impresion' => $fechaImpresion,
                                'items' => []
                            ];
                        }

                        $itemsAgrupados[$codigoArea]['con_ticket'][$nroTicket]['items'][] = $itemData;
                    } else {
                        $itemsAgrupados[$codigoArea]['sin_ticket'][] = $itemData;
                    }
                }
            }

            // Agrupar items sin ticket por producto, adicionales y observación
            foreach ($itemsAgrupados as $codigoArea => &$area) {
                if (!empty($area['sin_ticket'])) {
                    $area['sin_ticket'] = $this->agruparItemsSimilares($area['sin_ticket']);
                }
            }

            // Convertir con_ticket de objeto a array indexado
            foreach ($itemsAgrupados as $codigoArea => &$area) {
                $area['con_ticket'] = array_values($area['con_ticket']);

                // También agrupar items con ticket por producto, adicionales y observación dentro de cada ticket
                foreach ($area['con_ticket'] as &$ticketGroup) {
                    if (!empty($ticketGroup['items'])) {
                        $ticketGroup['items'] = $this->agruparItemsSimilares($ticketGroup['items']);
                    }
                }
            }

            // Eliminar áreas que no tienen items
            $itemsAgrupados = array_filter($itemsAgrupados, function ($area) {
                return !empty($area['con_ticket']) || !empty($area['sin_ticket']);
            });

            return VentaResponse::success($itemsAgrupados, 'Items agrupados por área y ticket');
        } catch (\Exception $e) {
            return VentaResponse::error('Error al obtener items: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene o crea el ticket del día para un área de despacho
     */
    private function obtenerOcrearTicketDia(AreaDespacho $areaDespacho): AreaDespachoTicket
    {
        $hoy = Carbon::today();

        $ticket = AreaDespachoTicket::where('area_despacho_id', $areaDespacho->id)
            ->whereDate('created_at', $hoy)
            ->first();

        if (!$ticket) {
            $ticket = AreaDespachoTicket::create([
                'area_despacho_id' => $areaDespacho->id,
                'ticket_actual' => '0',
            ]);
        }

        return $ticket;
    }

    /**
     * Genera el siguiente número de ticket para un área
     */
    private function generarSiguienteTicket(AreaDespacho $areaDespacho): string
    {
        $ticket = $this->obtenerOcrearTicketDia($areaDespacho);

        // Incrementar el ticket
        $nuevoNumero = intval($ticket->ticket_actual) + 1;
        $ticket->ticket_actual = (string)$nuevoNumero;
        $ticket->save();

        return $ticket->ticket_actual;
    }

    /**
     * Actualiza el campo nro_ticket en el pivot para múltiples items agrupados
     */
    private function actualizarNroTicketMultiple(array $itemsAgrupados, string $codigoArea, string $nroTicket, string $fechaImpresion): void
    {
        // Agrupar por producto_venta_id para actualizar eficientemente
        $itemsPorPivot = [];

        foreach ($itemsAgrupados as $item) {
            // Si el item tiene item_indices (está agrupado), usar esos índices
            if (isset($item['item_indices']) && is_array($item['item_indices'])) {
                foreach ($item['item_indices'] as $indiceData) {
                    $productoVentaId = $indiceData['producto_venta_id'];
                    $itemIndex = $indiceData['item_index'];

                    if (!isset($itemsPorPivot[$productoVentaId])) {
                        $itemsPorPivot[$productoVentaId] = [];
                    }
                    $itemsPorPivot[$productoVentaId][] = $itemIndex;
                }
            } else {
                // Compatibilidad con formato antiguo (item individual)
                $productoVentaId = $item['producto_venta_id'];
                $itemIndex = $item['item_index'];

                if (!isset($itemsPorPivot[$productoVentaId])) {
                    $itemsPorPivot[$productoVentaId] = [];
                }
                $itemsPorPivot[$productoVentaId][] = $itemIndex;
            }
        }

        // Actualizar cada pivot
        foreach ($itemsPorPivot as $productoVentaId => $indices) {
            $pivot = DB::table('producto_venta')
                ->where('id', $productoVentaId)
                ->first();

            if (!$pivot) {
                continue;
            }

            $nroTickets = json_decode($pivot->nro_ticket ?? '{}', true);

            // Inicializar estructura si no existe
            if (!is_array($nroTickets)) {
                $nroTickets = [];
            }

            // Actualizar cada item con el mismo ticket
            foreach ($indices as $itemIndex) {
                $nroTickets[$itemIndex] = [
                    'area_despacho' => $codigoArea,
                    'nro_ticket' => $nroTicket,
                    'fecha_impresion' => $fechaImpresion,
                ];
            }

            DB::table('producto_venta')
                ->where('id', $productoVentaId)
                ->update(['nro_ticket' => json_encode($nroTickets)]);
        }
    }

    /**
     * Compara dos arrays de adicionales para ver si son iguales
     */
    private function adicionalesSonIguales(array $adicionales1, array $adicionales2): bool
    {
        // Normalizar arrays: extraer solo los nombres de los adicionales y ordenarlos
        $normalizar = function ($adicionales) {
            $nombres = [];
            foreach ($adicionales as $adicional) {
                if (is_array($adicional)) {
                    $nombres = array_merge($nombres, array_keys($adicional));
                }
            }
            sort($nombres);
            return $nombres;
        };

        $nombres1 = $normalizar($adicionales1);
        $nombres2 = $normalizar($adicionales2);

        return $nombres1 === $nombres2;
    }

    /**
     * Genera una clave única para agrupar items
     */
    private function generarClaveAgrupacion(int $productoId, array $adicionales, ?string $observacion): string
    {
        $nombresAdicionales = [];
        foreach ($adicionales as $adicional) {
            if (is_array($adicional)) {
                $nombresAdicionales = array_merge($nombresAdicionales, array_keys($adicional));
            }
        }
        sort($nombresAdicionales);

        return $productoId . '|' . implode(',', $nombresAdicionales) . '|' . ($observacion ?? '');
    }

    /**
     * Agrupa items que tienen el mismo producto, adicionales y observación
     */
    private function agruparItemsSimilares(array $items): array
    {
        $agrupados = [];

        foreach ($items as $item) {
            $clave = $this->generarClaveAgrupacion(
                $item['producto_id'],
                $item['adicionales'],
                $item['observacion'] ?? null
            );

            if (!isset($agrupados[$clave])) {
                $agrupados[$clave] = [
                    'producto_venta_id' => $item['producto_venta_id'],
                    'producto_id' => $item['producto_id'],
                    'item_indices' => [], // Array de todos los índices agrupados
                    'nombre' => $item['nombre'],
                    'cantidad' => 0,
                    'observacion' => $item['observacion'] ?? null,
                    'adicionales' => $item['adicionales'],
                    'precio' => $item['precio'],
                ];
            }

            // Agregar el índice y aumentar la cantidad
            $agrupados[$clave]['item_indices'][] = [
                'producto_venta_id' => $item['producto_venta_id'],
                'item_index' => $item['item_index']
            ];
            $agrupados[$clave]['cantidad']++;
        }

        return array_values($agrupados);
    }

    /**
     * Obtiene todos los items sin ticket agrupados por área de despacho
     */
    private function obtenerItemsSinTicketPorArea(Venta $venta): array
    {
        $productos = $venta->productos()
            ->with(['areaDespacho'])
            ->get();

        $itemsPorArea = [];

        foreach ($productos as $producto) {
            // Solo procesar productos que tengan área de despacho
            if (!$producto->seccion || !$producto->areaDespacho) {
                continue;
            }

            $areaDespacho = $producto->areaDespacho;
            $codigoArea = $areaDespacho->codigo_area;

            // Obtener datos del pivot
            $pivot = DB::table('producto_venta')
                ->where('producto_id', $producto->id)
                ->where('venta_id', $venta->id)
                ->first();

            if (!$pivot) {
                continue;
            }

            $nroTickets = json_decode($pivot->nro_ticket ?? '{}', true);
            $adicionales = json_decode($pivot->adicionales ?? '{}', true);
            $cantidad = $pivot->cantidad;

            // Inicializar área si no existe
            if (!isset($itemsPorArea[$codigoArea])) {
                $itemsPorArea[$codigoArea] = [
                    'area' => $areaDespacho,
                    'items' => []
                ];
            }

            // Procesar cada item del producto
            for ($i = 1; $i <= $cantidad; $i++) {
                // Solo agregar items que NO tengan ticket
                if (!isset($nroTickets[$i]) || empty($nroTickets[$i])) {
                    $itemsPorArea[$codigoArea]['items'][] = [
                        'producto_venta_id' => $pivot->id,
                        'producto_id' => $producto->id,
                        'item_index' => $i,
                        'nombre' => $producto->nombre,
                        'cantidad' => 1,
                        'observacion' => $pivot->observacion,
                        'adicionales' => $adicionales[$i]['adicionales'] ?? [],
                        'precio' => $producto->precioReal(),
                    ];
                }
            }
        }

        // Agrupar items similares dentro de cada área
        foreach ($itemsPorArea as $codigoArea => &$areaData) {
            if (!empty($areaData['items'])) {
                $areaData['items'] = $this->agruparItemsSimilares($areaData['items']);
            }
        }

        // Eliminar áreas que no tienen items sin ticket
        return array_filter($itemsPorArea, function ($area) {
            return !empty($area['items']);
        });
    }

    /**
     * Imprime una comanda agrupando todos los items sin ticket de una sección
     */
    public function imprimirComanda(Venta $venta, string $codigoArea): VentaResponse
    {
        try {
            // Obtener todos los items sin ticket de esta área
            $itemsPorArea = $this->obtenerItemsSinTicketPorArea($venta);

            if (!isset($itemsPorArea[$codigoArea]) || empty($itemsPorArea[$codigoArea]['items'])) {
                return VentaResponse::error('No hay items pendientes de impresión para esta área');
            }

            $areaDespacho = $itemsPorArea[$codigoArea]['area'];
            $items = $itemsPorArea[$codigoArea]['items'];

            // Generar número de ticket para esta área
            $nroTicket = $this->generarSiguienteTicket($areaDespacho);
            $fechaImpresion = Carbon::now()->format('Y-m-d H:i:s');

            // Obtener id_impresora: primero del área, si no de la sucursal
            $idImpresora = $areaDespacho->id_impresora;
            if (!$idImpresora) {
                $sucursal = $venta->sucursale;
                $idImpresora = $sucursal->id_impresora ?? null;
            }

            if (!$idImpresora) {
                return VentaResponse::error('No hay impresora configurada para el área de despacho ni para la sucursal. Configure una impresora para poder imprimir comandas.');
            }

            // Cargar relaciones necesarias de la venta
            $venta->load(['cliente', 'mesa', 'sucursale']);

            // Preparar datos para impresión (limpiar items agrupados, remover item_indices y otros campos internos)
            $itemsParaImprimir = array_map(function ($item) {
                return [
                    'nombre' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'adicionales' => $item['adicionales'],
                    'observacion' => $item['observacion'] ?? null,
                ];
            }, $items);

            // Preparar datos para impresión (todos los items agrupados)
            $datosComanda = [
                'items' => $itemsParaImprimir,
                'nro_ticket' => $nroTicket,
                'area_despacho' => $areaDespacho->nombre_area,
                'fecha_impresion' => $fechaImpresion,
                'venta' => [
                    'id' => $venta->id,
                    'sucursale_id' => $venta->sucursale_id,
                    'tipo_entrega' => $venta->tipo_entrega,
                    'mesa_nombre' => $venta->mesa ? $venta->mesa->nombre_mesa : null,
                    'cliente_nombre' => $venta->cliente ? $venta->cliente->name : ($venta->usuario_manual ?? 'Anónimo'),
                ],
            ];

            // Generar contenido de comanda (igual que imprimirReciboVenta)
            $comanda = CustomPrint::imprimirComanda($datosComanda, $idImpresora);

            if (!$comanda) {
                // Si falla la generación, aún guardamos el ticket para que pueda reimprimirse después
                return VentaResponse::warning(
                    'Error al generar la comanda. El ticket #' . $nroTicket . ' fue generado y puede reimprimirse.',
                    ['nro_ticket' => $nroTicket]
                );
            }

            // Enviar a imprimir usando Printing facade (igual que imprimirReciboVenta)
            $resultadoImpresion = CustomPrint::imprimir($comanda, $idImpresora);

            if ($resultadoImpresion) {
                // Actualizar nro_ticket en todos los items agrupados
                $this->actualizarNroTicketMultiple($items, $areaDespacho->codigo_area, $nroTicket, $fechaImpresion);

                return VentaResponse::success(
                    ['nro_ticket' => $nroTicket],
                    "Comanda impresa exitosamente. Ticket: {$nroTicket}"
                );
            } else {
                // Si falla la impresión, aún guardamos el ticket para que pueda reimprimirse después
                return VentaResponse::warning(
                    'Error al imprimir la comanda. La impresora puede estar offline o no disponible. El ticket #' . $nroTicket . ' fue generado y puede reimprimirse cuando la impresora esté disponible.',
                    ['nro_ticket' => $nroTicket]
                );
            }
        } catch (\Exception $e) {
            return VentaResponse::error('Error al procesar comanda: ' . $e->getMessage());
        }
    }

    /**
     * Reimprime una comanda agrupando todos los items que comparten el mismo nro_ticket
     */
    public function reimprimirComanda(Venta $venta, string $nroTicket, string $codigoArea): VentaResponse
    {
        try {
            // Obtener todos los items con este ticket de esta área
            $productos = $venta->productos()
                ->with(['areaDespacho'])
                ->get();

            $items = [];
            $areaDespacho = null;
            $fechaImpresion = null;

            foreach ($productos as $producto) {
                if (!$producto->seccion || !$producto->areaDespacho) {
                    continue;
                }

                if ($producto->areaDespacho->codigo_area !== $codigoArea) {
                    continue;
                }

                if (!$areaDespacho) {
                    $areaDespacho = $producto->areaDespacho;
                }

                $pivot = DB::table('producto_venta')
                    ->where('producto_id', $producto->id)
                    ->where('venta_id', $venta->id)
                    ->first();

                if (!$pivot) {
                    continue;
                }

                $nroTickets = json_decode($pivot->nro_ticket ?? '{}', true);
                $adicionales = json_decode($pivot->adicionales ?? '{}', true);
                $cantidad = $pivot->cantidad;

                for ($i = 1; $i <= $cantidad; $i++) {
                    if (
                        isset($nroTickets[$i]) &&
                        !empty($nroTickets[$i]) &&
                        ($nroTickets[$i]['nro_ticket'] ?? null) === $nroTicket
                    ) {

                        if (!$fechaImpresion) {
                            $fechaImpresion = $nroTickets[$i]['fecha_impresion'] ?? null;
                        }

                        $items[] = [
                            'producto_venta_id' => $pivot->id,
                            'producto_id' => $producto->id,
                            'item_index' => $i,
                            'nombre' => $producto->nombre,
                            'cantidad' => 1,
                            'observacion' => $pivot->observacion,
                            'adicionales' => $adicionales[$i]['adicionales'] ?? [],
                            'precio' => $producto->precioReal(),
                        ];
                    }
                }
            }

            if (empty($items)) {
                return VentaResponse::error('No se encontraron items con el ticket #' . $nroTicket . ' para esta área');
            }

            if (!$areaDespacho) {
                return VentaResponse::error('No se pudo determinar el área de despacho');
            }

            // Agrupar items similares antes de imprimir
            $itemsAgrupados = $this->agruparItemsSimilares($items);

            // Obtener id_impresora: primero del área, si no de la sucursal
            $idImpresora = $areaDespacho->id_impresora;
            if (!$idImpresora) {
                $sucursal = $venta->sucursale;
                $idImpresora = $sucursal->id_impresora ?? null;
            }

            if (!$idImpresora) {
                return VentaResponse::error('No hay impresora configurada para el área de despacho ni para la sucursal. Configure una impresora para poder reimprimir comandas.');
            }

            // Cargar relaciones necesarias de la venta
            $venta->load(['cliente', 'mesa', 'sucursale']);

            // Preparar datos para impresión (limpiar items agrupados, remover item_indices y otros campos internos)
            $itemsParaImprimir = array_map(function ($item) {
                return [
                    'nombre' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'adicionales' => $item['adicionales'],
                    'observacion' => $item['observacion'] ?? null,
                ];
            }, $itemsAgrupados);

            // Preparar datos para impresión (todos los items agrupados)
            $datosComanda = [
                'items' => $itemsParaImprimir,
                'nro_ticket' => $nroTicket,
                'area_despacho' => $areaDespacho->nombre_area,
                'fecha_impresion' => $fechaImpresion,
                'venta' => [
                    'id' => $venta->id,
                    'sucursale_id' => $venta->sucursale_id,
                    'tipo_entrega' => $venta->tipo_entrega,
                    'mesa_nombre' => $venta->mesa ? $venta->mesa->nombre_mesa : null,
                    'cliente_nombre' => $venta->cliente ? $venta->cliente->name : ($venta->usuario_manual ?? 'Anónimo'),
                ],
            ];

            // Generar contenido de comanda (igual que imprimirReciboVenta)
            $comanda = CustomPrint::imprimirComanda($datosComanda, $idImpresora);

            if (!$comanda) {
                return VentaResponse::error('Error al generar la comanda para reimpresión.');
            }

            // Enviar a imprimir usando Printing facade (igual que imprimirReciboVenta)
            $resultadoImpresion = CustomPrint::imprimir($comanda, $idImpresora);

            if ($resultadoImpresion) {
                return VentaResponse::success(
                    ['nro_ticket' => $nroTicket],
                    "Comanda reimpresa exitosamente. Ticket: {$nroTicket}"
                );
            } else {
                return VentaResponse::error('Error al reimprimir la comanda. La impresora puede estar offline o no disponible.');
            }
        } catch (\Exception $e) {
            return VentaResponse::error('Error al procesar reimpresión: ' . $e->getMessage());
        }
    }
}
