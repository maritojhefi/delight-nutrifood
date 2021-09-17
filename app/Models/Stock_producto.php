<?php

namespace App\Models;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock_producto extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'fecha_venc','fecha_entrada','usuario_id','sucursale_id','producto_id'
       
    ];
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

}
