<?php

namespace App\Models;

use App\Models\Sucursale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Historial_venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario_id','cliente_id','sucursale_id','total','puntos','descuento','tipo','caja_id','saldo'
       
    ];

   
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
        ->withPivot('cantidad','estado_actual');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class,'usuario_id');
    }
    public function cliente()
    {
        return $this->belongsTo(User::class,'cliente_id');
    }
    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
}
