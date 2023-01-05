<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $fillable = [
        'historial_venta_id','user_id','monto','es_deuda','anulado','detalle','caja_id','historial_ventas_id','atendido_por','tipo'
       
    ];
    public function usuario()
    {
   
        return $this->belongsTo(User::class,'user_id');
    
    }
    public function venta()
    {
   
        return $this->belongsTo(Historial_venta::class,'historial_ventas_id');
    
    }
    public function venta2()
    {
   
        return $this->belongsTo(Historial_venta::class,'historial_venta_id');
    
    }
    public function atendidoPor()
    {
   
        return $this->belongsTo(User::class,'atendido_por');
    
    }
}
