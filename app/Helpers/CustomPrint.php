<?php

namespace App\Helpers;

use Exception;
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
    public static function imprimirReciboVenta(string $nombreCliente = null, $listaCuenta, $subtotal, $valorSaldo = 0, $descuentoProductos = 0, $otrosDescuentos = 0, $fecha = null, $observacion = null, $metodo = null, $historialVenta = null)
    {
        // dd($historialVenta);
        $nombre_impresora = 'POS-582';
        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);
        ob_start();

        // Encabezado
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 2);
        $img = EscposImage::load(public_path('delight_logo.jpg'));

        // MEJORA: Verificar si la imagen existe antes de intentar cargarla
        try {
            $printer->bitImageColumnFormat($img);
        } catch (Exception $e) {
            // Si falla la imagen, continuar sin ella
        }

        $printer->setTextSize(1, 1);
        $textoNombreTienda = GlobalHelper::limpiarTextoParaPOS('Nutri-Food/Comida Nutritiva');
        $printer->text($textoNombreTienda . "\n");
        $printer->feed(1); // MEJORA: Reducir a feed() sin parámetro o eliminar si no es necesario

        // MEJORA: Usar un separador consistente
        $textoLema = GlobalHelper::limpiarTextoParaPOS("'NUTRIENDO HÁBITOS!'");
        $printer->text($textoLema . "\n");
        $printer->feed(); // MEJORA: feed() sin parámetro es suficiente

        // MEJORA: Combinar líneas de contacto para ahorrar espacio
        $textoContacto = GlobalHelper::limpiarTextoParaPOS('Contacto: 78227629');
        $printer->text(str: $textoContacto . "\n");

        $textoDireccion = GlobalHelper::limpiarTextoParaPOS('Campero e/15 de abril y Madrid');
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
        }
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("--------\n");

        // Totales
        $printer->setTextSize(1, 1);
        $printer->text(sprintf('Subtotal: %.2f Bs' . "\n", floatval($subtotal)));
        if ($descuentoProductos != 0) {
            $printer->text(sprintf('Desc. productos: %.2f Bs' . "\n", floatval($descuentoProductos)));
        }
        if ($otrosDescuentos != 0) {
            $printer->text(sprintf('Otros descuentos: %.2f Bs' . "\n", floatval($otrosDescuentos)));
        }
        if (!isset($historialVenta)) {
            $totalPagado = $subtotal - $otrosDescuentos - $descuentoProductos;
            if ($valorSaldo != null && $valorSaldo != 0) {
                $printer->feed();
                $printer->text(sprintf('Saldo agregado: %.2f Bs' . "\n", floatval($valorSaldo)));
                $totalPagado -= $valorSaldo;
                $printer->feed();
            }
        } else {
            $totalPagado = $historialVenta->total_pagado;
            if ($valorSaldo != null && $valorSaldo != 0) {
                $printer->feed();
                $texto_saldo = $historialVenta->a_favor_cliente ? 'A favor cliente' : 'Deuda Generada';
                $printer->text(sprintf($texto_saldo . ': %.2f Bs' . "\n", floatval($valorSaldo)));
                $printer->feed();
            }
        }


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
            $img = EscposImage::load(public_path('delight_logo.jpg'));
            $printer->bitImageColumnFormat($img);
            $printer->setTextSize(1, 1);
            $printer->text('Nutri-Food/Eco-Tienda' . "\n");
            $printer->feed(1);
            $printer->text("'NUTRIENDO HABITOS!'" . "\n");
            $printer->feed(1);
            $printer->text('Contacto : 78227629' . "\n" . 'Campero e/15 de abril y Madrid' . "\n");
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
}
