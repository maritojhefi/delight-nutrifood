<?php

namespace App\Helpers;

use App\Models\Venta;
use App\Models\Producto;


class CreateList {

    
    static function crearlista(Venta $cuenta)
    {
        $listafiltrada=$cuenta->productos->pluck('nombre');
        $cantidadItems=$listafiltrada->count();
        $contando=$listafiltrada->countBy();
        
        $coleccion=collect($contando);
        $personalizado=collect();
        $total=0;
         foreach($coleccion as $nombre=>$cantidad)
         {
             $producto=Producto::where('nombre',$nombre)->first();
             if($producto->descuento!=0)
             {
                $subtotal=$producto->descuento*$cantidad;
                $personalizado->prepend(['id'=>$producto->id,'nombre'=>$nombre,'cantidad'=>$cantidad,'precio'=>$producto->descuento,'subtotal'=>$subtotal,'foto'=>$producto->pathAttachment()]);
               
             }
             else
             {
                $subtotal=$producto->precio*$cantidad;
                $personalizado->prepend(['id'=>$producto->id,'nombre'=>$nombre,'cantidad'=>$cantidad,'precio'=>$producto->precio,'subtotal'=>$subtotal,'foto'=>$producto->pathAttachment()]);
              
             }
             
             
             $total=$total+$subtotal;
         }  
         return [$personalizado,$total,$cantidadItems]; 
    }
}