<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario_id','cliente_id','sucursale_id','total','puntos','descuento','tipo'
       
    ];

   
    public function productos()
    {
        return $this->belongsToMany(Producto::class);
    }
    public function cliente()
    {
        return $this->belongsTo(User::class);
    }
}
