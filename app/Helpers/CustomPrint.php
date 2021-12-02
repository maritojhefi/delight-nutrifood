<?php

namespace App\Helpers;

use Rawilk\Printing\Facades\Printing;




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
}