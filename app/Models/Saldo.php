<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $fillable = [
        'historial_venta_id','user_id','monto','es_deuda','detalle','caja_id'
       
    ];
    public function usuario()
    {
   
        return $this->belongsTo(User::class);
    
    }
    public function venta()
    {
   
        return $this->belongsTo(Historial_venta::class,'historial_venta_id');
    
    }
    
}
