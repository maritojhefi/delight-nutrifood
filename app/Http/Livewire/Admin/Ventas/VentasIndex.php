<?php

namespace App\Http\Livewire\Admin\Ventas;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\User;
use App\Models\Plane;
use App\Models\Venta;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Sucursale;
use App\Models\Adicionale;
use Mike42\Escpos\Printer;
use App\Helpers\CreateList;
use Illuminate\Support\Str;
use App\Helpers\CustomPrint;
use Mike42\Escpos\EscposImage;
use App\Models\Historial_venta;
use App\Models\Saldo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

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
    public $descuento, $observacion;
    //variables para crear Cliente
    public $name, $cumpleano, $email, $direccion, $password, $password_confirmation;
    public $saldo, $valorSaldo=0, $deshabilitarBancos=false, $saldoRestante=0;
    protected $rules = [
        'sucursal' => 'required|integer',
    ];

    public function actualizarSaldo()
    {
        if($this->saldo)
        {
            $this->valorSaldo=$this->cuenta->total-$this->cuenta->descuento;
            $this->tipocobro='efectivo';     
            $this->emit('cambiarCheck');
            $this->deshabilitarBancos=true;
        }
        else
        {
            $this->valorSaldo=0;
            $this->deshabilitarBancos=false;
            $this->saldoRestante=0;
        }
        
    }
    public function controlarSaldo()
    {
        if($this->valorSaldo>$this->cuenta->total-$this->cuenta->descuento)
        {
            
            $this->valorSaldo=$this->cuenta->total-$this->cuenta->descuento;
            $this->tipocobro=false;
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"El saldo no debe ser mayor al monto a cobrar"
                ]);
            
        }
        else
        {
            $this->deshabilitarBancos=false;
            $this->saldoRestante=($this->cuenta->total-$this->cuenta->descuento)-$this->valorSaldo;
        }
        if($this->valorSaldo==$this->cuenta->total-$this->cuenta->descuento)
        {
            $this->tipocobro='efectivo';
            $this->emit('cambiarCheck');
            $this->deshabilitarBancos=true;
            $this->saldoRestante=0;
        }
        
    }
    public function guardarObservacion($idprod)
    {
       DB::table('producto_venta')->where('producto_id',$idprod)->where('venta_id',$this->cuenta->id)->update(['observacion'=>$this->observacion]);
       $this->dispatchBrowserEvent('alert',[
        'type'=>'success',
        'message'=>"Guardado"
        ]);
    }
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
    public function cambiarClienteACuenta(User $cliente)
    {
        $this->cuenta->cliente_id=$cliente->id;
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Se asigno a esta venta el cliente: ".$cliente->name
        ]);
        $this->cuenta->save();
        
    }
    public function cargarObservacion(Producto $prod)
    {
        //dd($prod);
        $observacion=$prod->ventas;
        
        foreach ($observacion->where('id',$this->cuenta->id) as $lista) {
            
            $this->observacion=$lista->pivot->observacion;
           
            break;
        }
        
        
    }

    public function crearCliente()
    {
        $this->validate([
            'name' => 'required|min:5|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);
 
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role_id'=> 4,
            'nacimiento'=>$this->cumpleano,
            'direccion'=>$this->direccion
            
        ]);
         $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Nuevo cliente: ".$this->name." creado!"
        ]);
        $this->reset();
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
           $this->reset('itemseleccionado','observacion');
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
        ->update(['total' => $resultado[1],'puntos' => $resultado[3]]);
        

        $this->cuenta->total=$resultado[1];
        $this->cuenta->puntos=$resultado[3];
        $this->itemsCuenta=$resultado[2];
        $this->reset(['adicionales','productoapuntado']);
        $this->saldo=false;
        $this->saldoRestante=0;
        
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
    public function actualizarstock(Producto $producto, $operacion, $cant)
    {
        $consulta=DB::table('producto_sucursale')->where('producto_id',$producto->id)->where('sucursale_id',$this->cuenta->sucursale_id)->orderBy('fecha_venc','asc')->get();
        $stock=$consulta->where('cantidad','!=',0)->first();
        $cantidadtotal=$consulta->pluck('cantidad');
        $sumado=$cantidadtotal->sum();
        
        if($consulta==null)
        {
            return null;
        }
        else
        {
            switch ($operacion) {
                case 'sumar':
                  if($stock==null)
                  {
                      return null;
                  }
                  else
                  {
                    $restado=$stock->cantidad-$cant;
                    DB::table('producto_sucursale')->where('id',$stock->id)->update(['cantidad'=>$restado]);
                  }
                  
                  
                    break;
                case 'restar':
                   $consultarestar=$consulta->sortByDesc('fecha_venc');
                   
                   foreach($consultarestar as $array)
                   {
                       $espacio=$array->max-$array->cantidad;
                       
                       if($espacio!=0)
                       {
                        if($espacio>=$cant)
                        {
                            
                         DB::table('producto_sucursale')->where('id',$array->id)->increment('cantidad',$cant);
                         break;
                        }
                        else
                        {
                         $cant=$cant-$espacio;
                         DB::table('producto_sucursale')->where('id',$array->id)->update(['cantidad'=>$array->max]);
                        }
                       }
                       
                   }
                    break;
                case 'sumarvarios':
                    if($sumado>$cant)
                    {
                         foreach($consulta as $array)
                         {
                             if($array->cantidad>$cant)
                             {
                                DB::table('producto_sucursale')->where('id',$array->id)->decrement('cantidad',$cant);
                                break;
                             }
                             else
                             {
                                $cant=$cant-$array->cantidad;
                                DB::table('producto_sucursale')->where('id',$array->id)->update(['cantidad'=>0]);
                             }
                         }
                    }
                    else
                    {
                        return false;
                    }
                    break;
               
            }
            return true;

        }
        
    }

    public function adicionar(Producto $producto){
        if($producto->contable==true)
        {
           $resultado= $this->actualizarstock($producto,'sumar',1);
           if($resultado==null)
           {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"No se puede agregar porque no existe stock para este producto"
            ]);
            
           }
           else
           {
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
        }
        else
        {
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
       
        
       
       
    }

    public function adicionarvarios(Producto $producto)
    {
        if($this->cantidadespecifica!=null)
        {
            if($producto->contable==true)
            {
                $resultado= $this->actualizarstock($producto,'sumarvarios',$this->cantidadespecifica);
                if($resultado==false)
                {
                    $this->dispatchBrowserEvent('alert',[
                        'type'=>'warning',
                        'message'=>"No se puede agregar porque no existe stock suficiente para este producto"
                    ]);
                    
                }
                else if($resultado==null)
                {
                    $this->dispatchBrowserEvent('alert',[
                        'type'=>'warning',
                        'message'=>"Este producto no tiene stock"
                    ]);
                }
                else
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
            }
            else
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
        if($producto->contable==true)
        {
           $this->actualizarstock($producto,'restar',1);
        }
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
        $registro=DB::table('producto_venta')->where('producto_id',$producto->id)->where('venta_id',$cuenta->id)->get()->first();
        if($producto->contable==true)
        {
           $this->actualizarstock($producto,'restar',$registro->cantidad);
        }
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
        $this->reset('tipocobro');
        $this->saldoRestante=0;
        $this->saldo=false;

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
        $this->validate(['tipocobro'=>'required']);
        $cajaactiva=Caja::where('sucursale_id',$this->cuenta->sucursale->id)->whereDate('created_at',Carbon::today())->first();
        
        if($cajaactiva!=null)
        {
            if($cajaactiva->estado=="abierto")
            {
                
                DB::table('cajas')->where('id',$cajaactiva->id)->increment('acumulado',$this->cuenta->total);
                $cuenta=Venta::find($this->cuenta->id);
                $cuentaguardada= Historial_venta::create([
                    'caja_id'=>$cajaactiva->id,
                    'usuario_id'=>auth()->user()->id,
                    'sucursale_id'=>$this->cuenta->sucursale_id,
                    'cliente_id'=>$this->cuenta->cliente_id,
                    'total'=>$this->cuenta->total,
                    'puntos'=>$this->cuenta->puntos,
                    'descuento'=>$this->cuenta->descuento,
                    'tipo'=>$this->tipocobro,
                    'saldo'=>$this->valorSaldo
                ]);

                $productos=$cuenta->productos;
                if($this->cuenta->cliente_id!=null)
                {
                    
                    DB::table('users')->where('id',$this->cuenta->cliente_id)->increment('puntos',$this->cuenta->puntos);
                }
                if($this->valorSaldo>0)
                {
                    Saldo::create([
                        'user_id'=>$this->cuenta->cliente_id,
                        'historial_venta_id'=>$cuentaguardada->id,
                        'monto'=>$this->valorSaldo,
                        'es_deuda'=>true
                    ]);
                    DB::table('users')->where('id',$this->cuenta->cliente_id)->increment('saldo',$this->valorSaldo);
                }
                 foreach($productos as $prod)
                 {
                     $cuentaguardada->productos()->attach($prod->id, ['cantidad' => $prod->pivot->cantidad,'adicionales'=>$prod->pivot->adicionales]);
                    
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
                    'type'=>'error',
                    'message'=>"La caja se encuentra cerrada!"
                ]);
            }
        }
        else
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Aun no se abrio la caja de hoy!"
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

    public function imprimir()
    {
        
        if($this->cuenta->sucursale->id_impresora)
        {
            
            $nombre_impresora = "POS-582"; 
            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 2);
            $printer->text("DELIGHT-NUTRIFOOD" . "\n");
            /*$img = EscposImage::load("public/delight_logo.jpg",false);
            $printer -> graphics($img);*/
            $printer->setTextSize(1, 1);
            $printer->text("Eco-Tienda" . "\n");
            $printer->feed(1);
            $printer->text("'NUTRIENDO HABITOS!'" . "\n");
            $printer->feed(1);
            $printer->text("Contacto : 78227629". "\n"."Campero e/15 de abril y Madrid" . "\n");
            $printer->setTextSize(2, 2);
            $printer->text("--------------\n");
            $printer->setTextSize(1, 1);
            foreach ($this->listacuenta as $list) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                if($list['cantidad']==1)
                {
                     $printer->text($list['cantidad'] . "x " . $list['nombre'] . "\n");
                }
                else
                {
                      $printer->text($list['cantidad'] . "x " . $list['nombre'] ."(".$list['precio']." c/u)". "\n"); 
                }
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $printer->text(' Bs ' . $list['subtotal'] . "\n");
            }
        $printer->text("--------\n");
        $printer->setTextSize(1, 1);
        $printer->text("Subtotal: ". $this->cuenta->total ."\n");
        $printer->text("Descuento: ". $this->cuenta->descuento ."\n");
        $printer->feed(1);
        $printer->setTextSize(1, 2);
        $printer->text("TOTAL: Bs ". $this->cuenta->total-$this->cuenta->descuento ."\n");
        $printer->feed(1);
        $printer->setTextSize(1, 1);
        $printer->text("Muchas gracias por preferirnos!\n");
        $printer->feed(1);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text(date("Y-m-d H:i:s") . "\n");
        $printer->feed(3);
          $respuesta=CustomPrint::imprimir($printer,$this->cuenta->sucursale->id_impresora);
          if($respuesta==true)
          {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Se imprimio el recibo correctamente"
            ]);
          }
          else if($respuesta==false)
          {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"La impresora no esta conectada"
            ]);
          }
           
        }
        else
        {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'warning',
                'message'=>"La sucursal no tiene una impresora activa"
            ]);
        }
       
    }
    public function render()
    {
        $ventas=Venta::orderBy('created_at','desc')->get();
        $usuarios=collect();
        $sucursales=Sucursale::pluck('id','nombre');
        $this->sucursal=$sucursales->first();
        $productos=Producto::where('estado','=','activo')->where(function (Builder $query) {return $query->where('codigoBarra',$this->search)->orWhere('nombre','LIKE','%'.$this->search.'%');})->take(5)->get();
        if($this->user!=null)
        {
            $usuarios=User::where('name','LIKE','%'.$this->user.'%')->orWhere('email','LIKE','%'.$this->user.'%')->take(3)->get();
        }
        return view('livewire.admin.ventas.ventas-index',compact('ventas','sucursales','productos','usuarios'))
        ->extends('admin.master')
        ->section('content');
    }
}
