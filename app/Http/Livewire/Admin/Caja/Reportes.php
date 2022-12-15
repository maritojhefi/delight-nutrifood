<?php

namespace App\Http\Livewire\Admin\Caja;

use Charts;
use App\Models\Caja;
use App\Models\Saldo;
use App\Models\Venta;
use Livewire\Component;
use App\Models\Producto;
use App\Helpers\CreateList;
use Illuminate\Support\Str;
use App\Helpers\CustomPrint;
use Chartisan\PHP\Chartisan;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Historial_venta;
use App\Imports\ProductosImport;
use Maatwebsite\Excel\Facades\Excel;

class Reportes extends Component
{
    use WithPagination;
   
    protected $paginationTheme = 'bootstrap';
    
    public $ventasHoy,$saldosHoy, $resumen, $lista, $cajaactiva,$reporteGeneral=true;
    public $fechaRecibo, $observacionRecibo,$clienteRecibo,$checkClientePersonalizado, $checkMetodoPagoPersonalizado, $metodoRecibo;
    public $imprimiendo=false,$cuenta,$listacuenta,$descuentoProductos;

    public function atras()
    {
        $this->reset('imprimiendo');
    }
    public function imprimir()
    {

        //QrCode::format('png')->size(150)->generate('https://delight-nutrifood.com/miperfil', public_path() . '/qrcode.png');
        if ($this->cuenta->sucursale->id_impresora) {

            $recibo = CustomPrint::imprimirReciboVenta(
                !$this->checkClientePersonalizado ? isset($this->cuenta->cliente->name)? Str::limit($this->cuenta->cliente->name, '20', ''):null: $this->clienteRecibo,
                $this->listacuenta,
                $this->cuenta->total,
                isset($this->valorSaldo) ? $this->valorSaldo : 0,
                $this->descuentoProductos,
                $this->cuenta->descuento,
                isset($this->fechaRecibo)?$this->fechaRecibo:null,
                isset($this->observacionRecibo)?$this->observacionRecibo:null,
                $this->checkMetodoPagoPersonalizado ? $this->metodoRecibo:''
            );
            $respuesta = CustomPrint::imprimir($recibo, $this->cuenta->sucursale->id_impresora);
            if ($respuesta == true) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => "Se imprimio el recibo correctamente"
                ]);
            } else if ($respuesta == false) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => "La impresora no esta conectada"
                ]);
            }
        } else {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'warning',
                'message' => "La sucursal no tiene una impresora activa"
            ]);
        }
    }
    public function modoImpresion(Historial_venta $venta)
    {
        $this->imprimiendo=true;
        $this->cuenta=$venta;
        $resultado = CreateList::crearListaHistorico($venta);
        $this->listacuenta = $resultado[0];
        $this->descuentoProductos = $resultado[4];
    }
    public function resetCaja()
    {
        $this->reset('cajaactiva');
    }
    public function cambiarReporte()
    {
        $this->reporteGeneral==true?$this->reporteGeneral=false:$this->reporteGeneral=true;
    }
    public function buscarCaja(Caja $caja)
    {
            $this->cajaactiva=$caja;
            $coleccion=collect();
            $personalizado=collect();
            $ventas=Historial_venta::where('caja_id',$caja->id)->get();
            $saldos=Saldo::where('caja_id',$caja->id)->where('es_deuda',false)->get();
            $this->ventasHoy=$ventas;
            $this->saldosHoy=$saldos;
             foreach($ventas as $list)
                 {
                    if($list->productos!=null)
                    {
                       foreach($list->productos as $lista)
                       {
                        $coleccion->prepend(['nombre'=>$lista->nombre,'cantidad'=>$lista->pivot->cantidad]);     
                       }
                    }
                 }
            $agrupado=$coleccion->groupBy('nombre');
            $total=0;
            foreach($agrupado as $nombre=>$cantidad)
            {
                $producto=Producto::where('nombre',$nombre)->first();
                $subtotal=($producto->descuento!=null?$producto->descuento:$producto->precio)*$cantidad->sum('cantidad');
                    $personalizado->prepend([
                        'nombre'=>$nombre,
                        'cantidad'=>$cantidad->sum('cantidad'),
                        'precio'=>$producto->descuento!=null?$producto->descuento:$producto->precio,
                        'subtotal'=>$subtotal,
                        'id'=>$producto->id]);
                $total=$total+$subtotal;
            }
            $this->resumen=$total;
            $ordenado=$personalizado->sortByDesc('cantidad');
            $this->lista= $ordenado;
    }
    public function render()
    {
       $cajas=Caja::orderBy('created_at','DESC')->paginate(10);
        return view('livewire.admin.caja.reportes',compact('cajas'))
        ->extends('admin.master')
        ->section('content');
    }
}
