<?php

namespace App\Helpers;

use Exception;
use Carbon\Carbon;
use Mike42\Escpos\Printer;
use Illuminate\Support\Str;
use App\Helpers\GlobalHelper;
use App\Models\ReciboImpreso;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Facades\Log;
use Rawilk\Printing\Facades\Printing;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class CustomPrint
{
    static function imprimir($stringprinter, $idprinter)
    {
        // dd($idprinter);
        $printeractivo = Printing::find($idprinter);

        try {
            if ($printeractivo->isOnline()) {
                $listaString = CustomPrint::getStringImpresion($stringprinter);
                Printing::newPrintTask()->printer($idprinter)->content($listaString)->send();
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
    public static function imprimirReciboVenta(string $nombreCliente = null, $listaCuenta, $subtotal, $valorSaldo = 0, $descuentoProductos = 0, $otrosDescuentos = 0, $fecha = null, $observacion = null, $metodo = null, $historialVenta = null, $totalAdicionales = 0)
    {
        // dd($historialVenta);
        $nombre_impresora = 'POS-582';
        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);
        ob_start();

        // Encabezado
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 2);
        $img = EscposImage::load(public_path(GlobalHelper::getValorAtributoSetting('logo_small')));
        // dd($img);
        // MEJORA: Verificar si la imagen existe antes de intentar cargarla
        try {
            $printer->bitImageColumnFormat($img);
        } catch (Exception $e) {
            // Si falla la imagen, continuar sin ella
        }

        $printer->setTextSize(1, 1);
        $textoNombreTienda = GlobalHelper::limpiarTextoParaPOS(GlobalHelper::getValorAtributoSetting('nombre_empresa'));
        $printer->text($textoNombreTienda . "\n");
        $printer->feed(1); // MEJORA: Reducir a feed() sin parámetro o eliminar si no es necesario

        // MEJORA: Usar un separador consistente
        $textoLema = GlobalHelper::limpiarTextoParaPOS("'" . GlobalHelper::getValorAtributoSetting('slogan') . "!'");
        $printer->text($textoLema . "\n");
        $printer->feed(); // MEJORA: feed() sin parámetro es suficiente

        // MEJORA: Combinar líneas de contacto para ahorrar espacio
        $textoContacto = GlobalHelper::limpiarTextoParaPOS('Contacto: ' . GlobalHelper::getValorAtributoSetting('telefono'));
        $printer->text(str: $textoContacto . "\n");

        $textoDireccion = GlobalHelper::limpiarTextoParaPOS(GlobalHelper::getValorAtributoSetting('direccion'));
        $printer->text($textoDireccion . "\n");

        if (isset($nombreCliente)) {
            $textoNombreCliente = GlobalHelper::limpiarTextoParaPOS($nombreCliente);
            $printer->text('Cliente: ' . $textoNombreCliente . "\n");
        }

        // MEJORA: Usar un método consistente para separadores
        $printer->setTextSize(2, 2);
        $printer->text(str_repeat('-', 16) . "\n"); // MEJORA: Separador consistente
        $printer->setTextSize(1, 1);

        // Detalle de productos
        foreach ($listaCuenta as $list) {
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $anchoLinea = 32; // Para impresora de 58mm

            if ($list['cantidad'] > 1) {
                // Caso cantidad > 1 (dos líneas)
                // Línea 1: Nombre del producto
                $textoLinea1 = sprintf('%dx %s', $list['cantidad'], GlobalHelper::limpiarTextoParaPOS($list['nombre']));
                $printer->text($textoLinea1 . "\n");

                // Línea 2: Precio unitario y total
                $precioUnitario = sprintf('(%.2f/u)', $list['precio']);
                $precioTotal = sprintf('%.2f', floatval($list['subtotal']));
                $espacios = $anchoLinea - strlen($precioUnitario) - strlen($precioTotal);
                $printer->text($precioUnitario . str_repeat(' ', $espacios) . $precioTotal . "\n");
            } else {
                // Caso cantidad = 1 (una sola línea)
                $descripcion = sprintf('%dx %s', $list['cantidad'], GlobalHelper::limpiarTextoParaPOS($list['nombre']));
                $precioTotal = sprintf('%.2f', floatval($list['subtotal']));

                $lineaCompleta = sprintf('%-24s%8s', substr($descripcion, 0, 24), $precioTotal);

                $printer->text($lineaCompleta . "\n");
            }

            // Mostrar adicionales desglosados si existen
            if (isset($list['adicionales_desglosados']) && is_array($list['adicionales_desglosados']) && count($list['adicionales_desglosados']) > 0) {
                foreach ($list['adicionales_desglosados'] as $adicional) {
                    $nombreAdicional = GlobalHelper::limpiarTextoParaPOS($adicional['nombre']);
                    $precioFormateado = sprintf('(Bs %.2f)', $adicional['precio_unitario']);

                    // Construir texto del adicional sin truncar
                    $textoAdicional = sprintf(
                        '  +x%d %s %s',
                        $adicional['cantidad'],
                        $nombreAdicional,
                        $precioFormateado
                    );

                    $printer->text($textoAdicional . "\n");
                }
            }

            // Agregar separación entre productos
            $printer->feed();
        }
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("--------\n");

        // Totales
        $printer->setTextSize(1, 1);
        $printer->text(sprintf('Subtotal: %.2f Bs' . "\n", floatval($subtotal)));
        if ($totalAdicionales != 0 && $totalAdicionales > 0) {
            $printer->text(sprintf('Total adicionales: +%.2f Bs' . "\n", floatval($totalAdicionales)));
        }
        if ($descuentoProductos != 0) {
            $printer->text(sprintf('Desc. productos: -%.2f Bs' . "\n", floatval($descuentoProductos)));
        }
        if ($otrosDescuentos != 0) {
            $printer->text(sprintf('Otros descuentos: -%.2f Bs' . "\n", floatval($otrosDescuentos)));
        }

        // Calcular total a pagar
        if (!isset($historialVenta)) {
            $totalAPagar = $subtotal + $totalAdicionales - $otrosDescuentos - $descuentoProductos;
            $totalPagado = $totalAPagar;
            if ($valorSaldo != null && $valorSaldo != 0) {
                $printer->feed();
                $printer->text(sprintf('Saldo agregado: -%.2f Bs' . "\n", floatval($valorSaldo)));
                $totalPagado -= $valorSaldo;
                $printer->feed();
            }
        } else {
            $totalAPagar = $subtotal + $totalAdicionales - $otrosDescuentos - $descuentoProductos;
            $totalPagado = $historialVenta->total_pagado;
            if ($valorSaldo != null && $valorSaldo != 0) {
                $printer->feed();
                $texto_saldo = $historialVenta->a_favor_cliente ? 'A favor cliente' : 'Deuda Generada';
                $signo = $historialVenta->a_favor_cliente ? '-' : '+';
                $printer->text(sprintf($texto_saldo . ': %s%.2f Bs' . "\n", $signo, floatval($valorSaldo)));
                $printer->feed();
            }
        }

        // MEJORA: Mostrar Total a pagar
        $printer->setTextSize(1, 1);
        $printer->text(sprintf('TOTAL A PAGAR: Bs %.2f' . "\n", $totalAPagar));
        $printer->feed();

        // MEJORA: Resaltar el total pagado
        $printer->setTextSize(1, 2);
        $printer->text(sprintf('TOTAL PAGADO: Bs %.2f' . "\n", $totalPagado));
        $printer->setTextSize(1, 1);

        // Métodos de pago
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $textoMetodos = GlobalHelper::limpiarTextoParaPOS('Métodos de pago:');
        $printer->text($textoMetodos . "\n");

        if (isset($metodo) && $metodo != '') {
            foreach ($metodo as $item) {
                $textoMetodoPago = GlobalHelper::limpiarTextoParaPOS($item->nombre_metodo_pago);
                $metodoTexto = sprintf('- %s', $textoMetodoPago);
                $montoTexto = sprintf('%.2f Bs.', $item->pivot->monto);

                // Ajuste para impresora de 58mm (32 caracteres)
                $lineaCompleta = sprintf(
                    '%-20s%12s',
                    substr($metodoTexto, 0, 20), // Truncar a 20 caracteres si es necesario
                    $montoTexto,
                );

                $printer->text($lineaCompleta . "\n");
            }
        } else {
            // Versión para "Efectivo" con alineación similar
            $lineaCompleta = sprintf('%-20s%12s', '- Efectivo', '0.00 Bs.');
            $printer->text($lineaCompleta . "\n");
        }

        $printer->setTextSize(2, 2);
        $printer->text(str_repeat('-', 16) . "\n");
        $printer->feed();

        // Pie de página
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        // MEJORA: Verificar si el QR existe
        try {
            $img = EscposImage::load(public_path('qrcode.png'));
            $printer->bitImageColumnFormat($img);
        } catch (Exception $e) {
            // Si falla la imagen, continuar sin ella
        }

        $printer->setTextSize(1, 1);
        if (isset($observacion)) {
            $printer->feed();
            $printer->text($observacion . "\n");
            $printer->feed();
        }

        // MEJORA: Reducir espacios y combinar mensajes
        $textoInvitacionDeligth = GlobalHelper::limpiarTextoParaPOS('Ingresa a nuestra plataforma!');
        $printer->text($textoInvitacionDeligth . "\n");

        $textoAgradecimiento = GlobalHelper::limpiarTextoParaPOS('Gracias por tu compra');
        $printer->text($textoAgradecimiento . "\n");

        $textoVuelePronto = GlobalHelper::limpiarTextoParaPOS('Vuelve pronto!');
        $printer->text($textoVuelePronto . "\n");

        if (isset($fecha)) {
            $printer->text($fecha . "\n");
        } else {
            $printer->text(date('Y-m-d H:i:s') . "\n");
        }

        // MEJORA: Reducir espacios finales (3 feeds son demasiado)
        $printer->feed(2); // Suficiente para cortar el ticket

        $contenidoRecibo = ob_get_clean();
        return $printer;
    }
    public static function getStringImpresion($recibo)
    {
        $lista = collect($recibo);
        $cont = 0;
        $listastring = '';
        foreach ($lista as $p) {
            $cont++;
            if ($cont == 2) {
                $boleta = collect($p);
                foreach ($boleta as $asd) {
                    for ($i = 0; $i < collect($asd)->count(); $i++) {
                        $listastring = $listastring . $asd[$i];
                    }
                    break;
                }
            }
        }

        return $listastring;
    }

    public static function imprimirTicket(string $nombreCliente = null, $listaCuenta, $subtotal, $valorSaldo = 0, $descuentoProductos = 0, $otrosDescuentos = 0, $fecha = null, $observacion = null, $metodo = '')
    {
        try {
            // 🖨️ Conexión a la impresora
            // Para Windows (con impresora compartida)
            $connector = new WindowsPrintConnector('POS-58'); // Reemplaza "TICKET" con el nombre de tu impresora

            $printer = new Printer($connector);
            ob_start();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 2);
            $img = EscposImage::load(public_path(GlobalHelper::getValorAtributoSetting('logo')));
            $printer->bitImageColumnFormat($img);
            $printer->setTextSize(1, 1);
            $printer->text(GlobalHelper::getValorAtributoSetting('nombre_empresa') . "\n");
            $printer->feed(1);
            $printer->text("'" . strtoupper(GlobalHelper::getValorAtributoSetting('slogan')) . "!'" . "\n");
            $printer->feed(1);
            $printer->text('Contacto : ' . GlobalHelper::getValorAtributoSetting('telefono') . "\n" . GlobalHelper::getValorAtributoSetting('direccion') . ' ' . "\n");
            if (isset($nombreCliente)) {
                $printer->text('Cliente: ' . $nombreCliente . "\n");
            }
            $printer->setTextSize(2, 2);
            $printer->text("--------------\n");
            $printer->setTextSize(1, 1);
            foreach ($listaCuenta as $list) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                if ($list['cantidad'] == 1) {
                    $printer->text($list['cantidad'] . 'x ' . $list['nombre'] . "\n");
                } else {
                    $printer->text($list['cantidad'] . 'x ' . $list['nombre'] . '(' . $list['precio'] . ' c/u)' . "\n");
                }
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $printer->text(' Bs ' . floatval($list['subtotal']) . "\n");
            }
            $printer->text("--------\n");
            $printer->setTextSize(1, 1);

            $printer->text('Subtotal: ' . floatval($subtotal) . " Bs\n");
            $printer->text('Descuento por productos: ' . floatval($descuentoProductos) . " Bs\n");
            $printer->text('Otros descuentos: ' . floatval($otrosDescuentos) . " Bs\n");
            if ($valorSaldo != null && $valorSaldo != 0) {
                $printer->feed(1);
                $printer->text('Saldo agregado: ' . floatval($valorSaldo) . " Bs\n");
                $printer->feed(1);
                $printer->setTextSize(1, 2);
                $printer->text('TOTAL PAGADO: Bs ' . $subtotal - $otrosDescuentos - $valorSaldo - $descuentoProductos . "\n");
            } else {
                $printer->feed(1);
                $printer->setTextSize(1, 2);
                $printer->text('TOTAL PAGADO: Bs ' . $subtotal - $otrosDescuentos - $descuentoProductos . "\n");
            }
            $printer->setTextSize(1, 1);
            if (isset($metodo) && $metodo != '') {
                $printer->text('Metodo: ' . $metodo . "\n");
            }

            $printer->feed(1);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $img = EscposImage::load(public_path('qrcode.png'));
            $printer->bitImageColumnFormat($img);
            $printer->setTextSize(1, 1);
            if (isset($observacion)) {
                $printer->feed(1);
                $printer->text($observacion . "\n");
                $printer->feed(1);
            }

            $printer->text("Ingresa a nuestra plataforma!\n");
            $printer->feed(1);
            $printer->text("Gracias por tu compra\n");
            $printer->text("Vuelve pronto!\n");
            $printer->feed(1);
            if (isset($fecha)) {
                $printer->text($fecha . "\n");
            } else {
                $printer->text(date('Y-m-d H:i:s') . "\n");
            }

            $printer->feed(2);
            $printer->cut();
            $printer->close();

            return true;
        } catch (Exception $e) {
            Log::error('Error al imprimir: ' . $e->getMessage());
            return false;
        }
    }

    public static function imprimirComanda(array $datosComanda, ?string $idImpresora = null)
    {
        // Validar que haya id_impresora
        if (!$idImpresora) {
            Log::error('No se proporcionó id_impresora para imprimir comanda');
            return false;
        }

        // Verificar que la impresora esté online
        try {
            $printeractivo = Printing::find($idImpresora);
            if (!$printeractivo || !$printeractivo->isOnline()) {
                Log::warning('Impresora offline o no encontrada: ' . $idImpresora);
                return false;
            }
        } catch (\Throwable $th) {
            Log::error('Error al verificar impresora: ' . $th->getMessage());
            return false;
        }

        // Usar WindowsPrintConnector SOLO para generar el contenido codificado (no para imprimir)
        // El nombre de la impresora es solo para el connector, no se usa para imprimir realmente
        try {
            $nombre_impresora = 'POS-58'; // Nombre dummy para el connector
            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);
            ob_start();

            // Encabezado
            $printer->setJustification(Printer::JUSTIFY_CENTER);

            // Área de despacho
            $printer->setTextSize(1, 2);
            $textoArea = GlobalHelper::limpiarTextoParaPOS($datosComanda['area_despacho']);
            $printer->text($textoArea . "\n");
            $printer->setTextSize(1, 1);

            // Separador
            $printer->text(str_repeat('-', 32) . "\n");

            // Número de ticket
            $printer->setTextSize(1, 2);
            $printer->text('TICKET #' . $datosComanda['nro_ticket'] . "\n");
            $printer->setTextSize(1, 1);

            // Separador
            $printer->text(str_repeat('-', 32) . "\n");

            // Items agrupados
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            if (isset($datosComanda['items']) && is_array($datosComanda['items'])) {
                // Múltiples items agrupados
                foreach ($datosComanda['items'] as $item) {
                    $printer->setTextSize(1, 2);
                    $nombreProducto = GlobalHelper::limpiarTextoParaPOS($item['nombre']);
                    $printer->text($item['cantidad'] . 'x ' . $nombreProducto . "\n");
                    $printer->setTextSize(1, 1);

                    // Adicionales del item
                    if (!empty($item['adicionales']) && is_array($item['adicionales'])) {
                        foreach ($item['adicionales'] as $adicional) {
                            if (is_array($adicional)) {
                                foreach ($adicional as $nombre => $precio) {
                                    $nombreAdicional = GlobalHelper::limpiarTextoParaPOS($nombre);
                                    $printer->text('  + ' . $nombreAdicional . "\n");
                                }
                            }
                        }
                    }

                    // Observación del item
                    if (!empty($item['observacion'])) {
                        $observacion = GlobalHelper::limpiarTextoParaPOS($item['observacion']);
                        $printer->text('OBS: ' . $observacion . "\n");
                    }
                }
            } else {
                // Compatibilidad con formato antiguo (un solo producto)
                $printer->setTextSize(1, 2);
                $nombreProducto = GlobalHelper::limpiarTextoParaPOS($datosComanda['producto']['nombre']);
                $printer->text($datosComanda['producto']['cantidad'] . 'x ' . $nombreProducto . "\n");
                $printer->setTextSize(1, 1);

                // Adicionales
                if (!empty($datosComanda['adicionales']) && is_array($datosComanda['adicionales'])) {
                    foreach ($datosComanda['adicionales'] as $adicional) {
                        if (is_array($adicional)) {
                            foreach ($adicional as $nombre => $precio) {
                                $nombreAdicional = GlobalHelper::limpiarTextoParaPOS($nombre);
                                $printer->text('  + ' . $nombreAdicional . "\n");
                            }
                        }
                    }
                }

                // Observación
                if (!empty($datosComanda['observacion'])) {
                    $observacion = GlobalHelper::limpiarTextoParaPOS($datosComanda['observacion']);
                    $printer->text('OBS: ' . $observacion . "\n");
                }
            }

            // Separador final
            $printer->text(str_repeat('-', 32) . "\n");

            // Información adicional de la venta
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            if (isset($datosComanda['venta'])) {
                $venta = $datosComanda['venta'];

                // Tipo de entrega
                if (!empty($venta['tipo_entrega'])) {
                    $tipoEntrega = GlobalHelper::limpiarTextoParaPOS('Tipo: ' . ucfirst($venta['tipo_entrega']));
                    $printer->text($tipoEntrega . "\n");
                }

                // Mesa
                if (!empty($venta['mesa_numero'])) {
                    $mesa = GlobalHelper::limpiarTextoParaPOS('Mesa: #' . $venta['mesa_numero']);
                    $printer->text($mesa . "\n");
                }

                // Cliente
                if (!empty($venta['cliente_nombre'])) {
                    $cliente = GlobalHelper::limpiarTextoParaPOS('Cliente: ' . $venta['cliente_nombre']);
                    $printer->text($cliente . "\n");
                }
            }

            // Fecha y hora de impresión
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            if (isset($datosComanda['fecha_impresion'])) {
                $fechaImpresion = Carbon::parse($datosComanda['fecha_impresion'])->format('d/m/Y H:i:s');
                $printer->text('Impreso: ' . $fechaImpresion . "\n");
            } else {
                $printer->text(date('d/m/Y H:i:s') . "\n");
            }
            $printer->feed(5);

            $printer->cut();
            $contenidoComanda = ob_get_clean();

            // NO cerrar el printer aquí, solo retornar el objeto para que getStringImpresion lo procese
            // Luego se enviará usando Printing::newPrintTask()
            return $printer;
        } catch (\Exception $e) {
            Log::error('Error al generar contenido de comanda: ' . $e->getMessage());
            return false;
        }
    }
}
