<?php

namespace App\Http\Livewire\Admin\Ventas;

use App\Models\User;
use App\Models\Plane;
use App\Models\Venta;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Models\Adicionale;
use App\Helpers\CreateList;
use Illuminate\Support\Str;
use App\Models\Historial_venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentasIndex extends Component
{
    public $sucursal;
    public $cuenta;
    public $search;
    public $itemsCuenta;
    public $listacuenta;
    public $user;
    public $cliente;
    public $cantidadespecifica;
    public $adicionales;
    public $productoapuntado;
    public $itemseleccionado;
    public $array;
    public $tipocobro;
    public $descuento;

    protected $rules = [
        'sucursal' => 'required|integer',
    ];

    public function crear(){
        $this->validate();
       
        Venta::create([
            'usuario_id'=>auth()->user()->id,
            'sucursale_id'=>$this->sucursal,
            'cliente_id'=>$this->cliente,
        ]);
        $this->reset(['user','cliente','sucursal']);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Nueva venta creada "
        ]);
    }
    public function seleccionaritem($numero)
    {
      
      /*  foreach($this->productoapuntado->ventas->where('id','7') as $asd)
        {
            dd($asd->pivot->cantidad);
        }*/
        
        $this->itemseleccionado=$numero;
    }

    public function mostraradicionales(Producto $producto)
    {
        if($producto->medicion=="unidad")
        {
            $adicionales=$producto->subcategoria->adicionales;
             $this->adicionales=$adicionales;
            foreach($producto->ventas->where('id',$this->cuenta->id) as $item)
            {
                $this->array=json_decode($item->pivot->adicionales,true); 
             /*   foreach($this->array as $lista)
                {
                   foreach($lista as $nombre=>$precio)
                   {
                       foreach($precio as $item=>$prec)
                       {
                           dd($prec);
                       }
                   }
                }*/
            }
           $this->reset('itemseleccionado');
            $this->productoapuntado=$producto;
           
        }
        else
        {
            $this->reset(['adicionales','productoapuntado']);
        }
       

    }

    public function seleccionarcliente($id, $name)
    {
        $this->cliente=$id;
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Usuario ".$name." seleccionado"
        ]);
    }
    

    public function actualizaradicionales($idproducto, $operacion)
    {
        $registro = DB::table('producto_venta')->where('producto_id',$idproducto)->where('venta_id',$this->cuenta->id)->get();
       
        if($registro->count()!=0)
        {
            $listaadicionales = $registro[0]->adicionales;
            if($listaadicionales==null)
            {
                $string='{"1":[]}';
                DB::table('producto_venta')->where('producto_id',$idproducto)->where('venta_id',$this->cuenta->id)->update(['adicionales' => $string]);
            }
            else
            {
               
                $json=json_decode($listaadicionales, true);
               
                $cantidad=collect($json)->count();
                if($operacion=="sumar")
                {
                    $string[]=[];
                    //dd($string);
                    array_push($json, $string[0]);
                   //dd($json);
                    DB::table('producto_venta')->where('producto_id',$idproducto)->where('venta_id',$this->cuenta->id)->update(['adicionales' => $json]);
                }
                else if($operacion=="muchos")
                {
                    $string[]=[];
                    for ($i=0; $i <$this->cantidadespecifica ; $i++) { 
                        array_push($json, $string[0]);
                    }
                    
                    DB::table('producto_venta')->where('producto_id',$idproducto)->where('venta_id',$this->cuenta->id)->update(['adicionales' => $json]);
                }
                else if($registro[0]->cantidad>0)
                {
                    unset($json[$cantidad]);
                    $string=json_encode($json); 
                    DB::table('producto_venta')->where('producto_id',$idproducto)->where('venta_id',$this->cuenta->id)->update(['adicionales' => $string]);
    
                }
              
            }
        }
        
    }

    public function actualizarlista($cuenta){
        $resultado=CreateList::crearlista($cuenta);
        $this->listacuenta=$resultado[0];
        DB::table('ventas')
        ->where('id', $cuenta->id)
        ->update(['total' => $resultado[1]]);
        DB::table('ventas')
        ->where('id', $cuenta->id)
        ->update(['puntos' => $resultado[3]]);
        $this->cuenta->total=$resultado[1];
        $this->cuenta->puntos=$resultado[3];
        $this->itemsCuenta=$resultado[2];
        $this->reset(['adicionales','productoapuntado']);
    }

    public function agregaradicional(Adicionale $adicional, $item)
    {
        //dd($this->productoapuntado);
           if($this->productoapuntado->medicion=="unidad")
            {
                
                $pivot = DB::table('producto_venta')->where('producto_id',$this->productoapuntado->id)->where('venta_id',$this->cuenta->id)->first();
                $string=$pivot->adicionales;
               $array=json_decode($string, true);
               for ($i=1; $i <= $item; $i++) { 
                   if($i==$item)
                   {
                    array_push($array[$i], [$adicional->nombre=>$adicional->precio]);
                    //dd($array[$i]);
                   }
                   
               }
               $lista=json_encode($array);
               DB::table('producto_venta')->where('producto_id',$this->productoapuntado->id)->where('venta_id',$this->cuenta->id)->update(['adicionales' => $lista]);
               $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Agregado!"
            ]);
              
            $resultado=CreateList::crearlista($this->cuenta);
            $this->listacuenta=$resultado[0];
            DB::table('ventas')
            ->where('id', $this->cuenta->id)
            ->update(['total' => $resultado[1]]);
            $this->cuenta->total=$resultado[1];
            $this->cuenta->puntos=$resultado[3];
            $this->itemsCuenta=$resultado[2];
           
            }

    }

    public function adicionar(Producto $producto){
        $cuenta = Venta::find($this->cuenta->id);
        $registro = DB::table('producto_venta')->where('producto_id',$producto->id)->where('venta_id',$cuenta->id)->get();
       
        if($registro->count()==0)
        {
           $cuenta->productos()->attach($producto->id); 
        }
        else
        {
            DB::table('producto_venta')
                  ->where('venta_id', $cuenta->id)
                  ->where('producto_id', $producto->id)
                  ->increment('cantidad',1);
        }
        //actualiza lista de adicionales en el atributo
        if($producto->medicion=="unidad")
        {
            $this->actualizaradicionales($producto->id, 'sumar');
        }

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se agrego 1 ".$producto->nombre." a esta venta"
        ]);
       $this->actualizarlista($cuenta);
    }

    public function adicionarvarios(Producto $producto)
    {
        if($this->cantidadespecifica!=null)
        {
            $cuenta = Venta::find($this->cuenta->id); 
            DB::table('producto_venta')
            ->where('venta_id', $cuenta->id)
            ->where('producto_id', $producto->id)
            ->increment('cantidad',$this->cantidadespecifica);  
           $this->actualizarlista($cuenta);
         
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Se agrego ".$this->cantidadespecifica." ".$producto->nombre."(s) a esta venta"
            ]);
            if($producto->medicion=="unidad")
            {
                $this->actualizaradicionales($producto->id, 'muchos');
            }
            $this->reset('cantidadespecifica');
        }
        else
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Fije una cantidad"
            ]);
        }
    }

    public function eliminaruno(Producto $producto){
        $cuenta = Venta::find($this->cuenta->id);
        $registro = DB::table('producto_venta')->where('producto_id',$producto->id)->where('venta_id',$cuenta->id)->get();
       
        if($registro[0]->cantidad==1)
        {
            $cuenta->productos()->detach($producto->id);
        }
       else
       {
        DB::table('producto_venta')
        ->where('venta_id', $cuenta->id)
        ->where('producto_id', $producto->id)
        ->decrement('cantidad',1);   
       }
      $this->actualizarlista($cuenta);
      $this->actualizaradicionales($producto->id, 'restar');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se elimino 1 ".$producto->nombre." de esta venta"
        ]);
    }

    public function eliminarproducto(Producto $producto){
        $cuenta = Venta::find($this->cuenta->id);
        $cuenta->productos()->detach($producto->id);
        
        $this->actualizarlista($cuenta);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se elimino a ".$producto->nombre." de esta venta"
        ]);
    }
    
    public function seleccionar(Venta $venta){
        $this->cuenta=$venta;
        $listafiltrada=$venta->productos->pluck('nombre');
        
      $this->actualizarlista($venta);
    }

    public function eliminar(Venta $venta)
    {
            $venta->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Venta eliminada"
            ]);
            if($this->cuenta!=null)
            {
                if($venta->id==$this->cuenta->id)
                {
                    $this->reset();
                }
            }  
    }
    public function agregardesdeplan($user, $plan, $producto)
    {
       
        $planusuario=DB::table('plane_user')
        ->where('user_id', $user)
        ->where('plane_id',$plan)
        ->decrement('restante',1);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se resto una unidad al plan"
        ]);

        $ventaactual= Venta::find($this->cuenta->id);
        $productoplan=Producto::find($producto);
        if($productoplan->descuento!=null || $productoplan->descuento!="")
        {
            $ventaactual->descuento=$ventaactual->descuento+$productoplan->descuento;
        }
        else
        {
            $ventaactual->descuento=$ventaactual->descuento+$productoplan->precio;
        }
        
        $ventaactual->update();
        $this->adicionar($productoplan);
        $ventaactual=Venta::find($this->cuenta->id);
        $this->cuenta=$ventaactual;
    }
    
    public function cobrar()
    {
        if($this->tipocobro!=null)
        {
            $cuenta=Venta::find($this->cuenta->id);
            $cuentaguardada= Historial_venta::create([
                'usuario_id'=>$this->cuenta->usuario_id,
                'sucursale_id'=>$this->cuenta->sucursale_id,
                'cliente_id'=>$this->cuenta->cliente_id,
                'total'=>$this->cuenta->total,
                'puntos'=>$this->cuenta->puntos,
                'descuento'=>$this->cuenta->descuento,
                'tipo'=>$this->tipocobro
            ]);
            $productos=$cuenta->productos;
            
             foreach($productos as $prod)
             {
                 
                $cuentaguardada->productos()->attach($prod->id);
        
             }
             $cuenta->productos()->detach();
                $cuenta->delete();
                
                $this->reset('cuenta');
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Venta finalizada!"
                ]);
        }
        else
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"Seleccione un tipo de pago!"
            ]);
        }
       
    }

    public function editardescuento(){
        DB::table('ventas')->where('id',$this->cuenta->id)->update(['descuento'=>$this->descuento]);
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Descuento actualizado!"
        ]);
        $cuenta=Venta::find($this->cuenta->id);
        $this->cuenta=$cuenta;
        $this->reset('descuento');
    }

    public function render()
    {
        $ventas=Venta::orderBy('created_at','desc')->get();
        $usuarios=collect();
        $sucursales=Sucursale::pluck('id','nombre');
        $productos=Producto::where('codigoBarra',$this->search)->orWhere('nombre','LIKE','%'.$this->search.'%')->take(5)->get();
        if($this->user!=null)
        {
            $usuarios=User::where('name','LIKE','%'.$this->user.'%')->orWhere('email','LIKE','%'.$this->user.'%')->take(3)->get();
        }
        return view('livewire.admin.ventas.ventas-index',compact('ventas','sucursales','productos','usuarios'))
        ->extends('admin.master')
        ->section('content');
    }
}
