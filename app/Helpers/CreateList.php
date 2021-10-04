<?php

namespace App\Helpers;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;


class CreateList {

    
    static function crearlista(Venta $cuenta)
    {
       
         $registro = DB::table('producto_venta')->where('venta_id',$cuenta->id)->get();
        
         $cantidadItems=$registro->count();
         $personalizado=collect();
         $puntos=0;
         $total=0;
         foreach($registro as $item)
         {
             
            $producto=Producto::find($item->producto_id);
            if($producto->descuento!=0)
            {
                $subtotal=$producto->descuento*$item->cantidad;
               $personalizado->prepend(['id'=>$producto->id,'nombre'=>$producto->nombre,'medicion'=>$producto->medicion,'cantidad'=>$item->cantidad,'precio'=>$producto->descuento,'subtotal'=>$subtotal,'foto'=>$producto->pathAttachment()]);
              
            }
            else
            {
                $subtotal=$producto->precio*$item->cantidad;
               $personalizado->prepend(['id'=>$producto->id,'nombre'=>$producto->nombre,'medicion'=>$producto->medicion,'cantidad'=>$item->cantidad,'precio'=>$producto->precio,'subtotal'=>$subtotal,'foto'=>$producto->pathAttachment()]);
              
            }
            $puntos=$puntos+($producto->puntos*$item->cantidad);
             $total=$total+$subtotal;
         }
         
         return [$personalizado,$total,$cantidadItems,$puntos]; 
    }

    static function crearlistaantiguo(Venta $cuenta)
    {
       

        $listafiltrada=$cuenta->productos->pluck('nombre');
       
        $cantidadItems=$listafiltrada->count();
        $contando=$listafiltrada->countBy();
        
        $coleccion=collect($contando);
        
        $personalizado=collect();
        $total=0;
        $puntos=0;
         foreach($coleccion as $nombre=>$cantidad)
         {
             $producto=Producto::where('nombre',$nombre)->first();
             if($producto->descuento!=0 && $producto->descuento!=null)
             {
                $subtotal=$producto->descuento*$cantidad;
                $personalizado->prepend(['id'=>$producto->id,'nombre'=>$nombre,'cantidad'=>$cantidad,'precio'=>$producto->descuento,'subtotal'=>$subtotal,'foto'=>$producto->pathAttachment()]);
               
             }
             else
             {
                $subtotal=$producto->precio*$cantidad;
                $personalizado->prepend(['id'=>$producto->id,'nombre'=>$nombre,'cantidad'=>$cantidad,'precio'=>$producto->precio,'subtotal'=>$subtotal,'foto'=>$producto->pathAttachment()]);
              
             }
             
             $puntos=$puntos+($producto->puntos*$cantidad);
             $total=$total+$subtotal;
         }  
         return [$personalizado,$total,$cantidadItems,$puntos]; 
    }
}