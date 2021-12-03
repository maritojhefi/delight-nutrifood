<?php

namespace App\Models;

use App\Models\Caja;
use App\Models\User;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Traslado;
use App\Models\Historial_venta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sucursale extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'nombre','direccion','telefono','id_impresora'
       
    ];
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
        ->withPivot('producto_id','usuario_id','cantidad','fecha_venc');

    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function historial_ventas()
    {
        return $this->hasMany(Historial_venta::class);
    }
    public function cajas()
    {
        return $this->hasMany(Caja::class);
    }
   
}
