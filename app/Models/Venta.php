<?php

namespace App\Models;

use App\Models\User;
use App\Models\Producto;
use App\Models\Sucursale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario_id','cliente_id','sucursale_id','total','puntos','descuento','tipo'
       
    ];

    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
        ->withPivot('cantidad','estado_actual','adicionales');
    }
    public function cliente()
    {
        return $this->belongsTo(User::class);
    }
}
