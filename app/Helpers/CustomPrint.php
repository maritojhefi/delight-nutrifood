<?php

namespace App\Helpers;

use Mike42\Escpos\Printer;
use Illuminate\Support\Str;
use Mike42\Escpos\EscposImage;
use Rawilk\Printing\Facades\Printing;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;




class CustomPrint {

    
    static function imprimir($stringprinter,$idprinter)
    {
      $printeractivo=Printing::find($idprinter);
      
      if($printeractivo->isOnline())
      {
        $lista= collect($stringprinter);      
        $cont=0;    
        $listastring="";
        foreach($lista as $p)
        {
            $cont++;
            if($cont==2)
            {
                $boleta=collect($p);           
                foreach($boleta as $asd)
                {         
                  for ($i=0; $i < collect($asd)->count() ; $i++) { 
                    $listastring=$listastring.$asd[$i];             
                }
                break;              
                }         
            }
        }
        Printing::newPrintTask()
        ->printer($idprinter)
        ->content($listastring)
        ->send();
  
        return true;
      }
      else
      {
          return false;
      }
      
    }
    public static function imprimirReciboVenta(string $nombreCliente=null,$listaCuenta,$subtotal, $valorSaldo=0,$descuentoProductos=0,$otrosDescuentos=0, $fecha=null,$observacion=null)
    {
      $nombre_impresora = "POS-582";
            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 2);
            $img = EscposImage::load(public_path("delight_logo.jpg"));
            $printer->bitImageColumnFormat($img);
            $printer->setTextSize(1, 1);
            $printer->text("Nutri-Food/Eco-Tienda" . "\n");
            $printer->feed(1);
            $printer->text("'NUTRIENDO HABITOS!'" . "\n");
            $printer->feed(1);
            $printer->text("Contacto : 78227629" . "\n" . "Campero e/15 de abril y Madrid" . "\n");
            if (isset($nombreCliente)) {
                $printer->text("Cliente: " . Str::limit($nombreCliente, '20', '') . "\n");
            }
            $printer->setTextSize(2, 2);
            $printer->text("--------------\n");
            $printer->setTextSize(1, 1);
            foreach ($listaCuenta as $list) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                if ($list['cantidad'] == 1) {
                    $printer->text($list['cantidad'] . "x " . $list['nombre'] . "\n");
                } else {
                    $printer->text($list['cantidad'] . "x " . $list['nombre'] . "(" . $list['precio'] . " c/u)" . "\n");
                }
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $printer->text(' Bs ' . floatval($list['subtotal']) . "\n");
            }
            $printer->text("--------\n");
            $printer->setTextSize(1, 1);

            $printer->text("Subtotal: " . floatval($subtotal) . " Bs\n");
            $printer->text("Descuento por productos: " . floatval($descuentoProductos) . " Bs\n");
            $printer->text("Otros descuentos: " . floatval($otrosDescuentos) . " Bs\n");
            if ($valorSaldo != null && $valorSaldo != 0) {
                $printer->feed(1);
                $printer->text("Saldo agregado: " . floatval($valorSaldo) . " Bs\n");
                $printer->feed(1);
                $printer->setTextSize(1, 2);
                $printer->text("TOTAL PAGADO: Bs " . $subtotal - $otrosDescuentos - $valorSaldo - $descuentoProductos . "\n");
                $printer->feed(1);
            } else {
                $printer->feed(1);
                $printer->setTextSize(1, 2);
                $printer->text("TOTAL PAGADO: Bs " . $subtotal - $otrosDescuentos - $descuentoProductos . "\n");
                $printer->feed(1);
            }
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $img = EscposImage::load(public_path("qrcode.png"));
            $printer->bitImageColumnFormat($img);
            $printer->setTextSize(1, 1);
            if (isset($observacion)) {
                $printer->text($observacion."\n");
                $printer->feed(1);
            }
            
            $printer->text("Ingresa a nuestra plataforma!\n");
            $printer->feed(1);
            $printer->text("Gracias por tu compra\n");
            $printer->text("Vuelve pronto!\n");
            $printer->feed(1);
            if (isset($fecha)) {
                $printer->text($fecha . "\n");
            }
            else
            {
                $printer->text(date("Y-m-d H:i:s") . "\n");
            }
            
            $printer->feed(3);
            return $printer;
    }
}