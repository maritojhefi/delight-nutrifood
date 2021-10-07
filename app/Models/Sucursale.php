<?php

namespace App\Models;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Traslado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sucursale extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'nombre','direccion','telefono'
       
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
   
}
