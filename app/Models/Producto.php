<?php

namespace App\Models;

use App\Models\Sucursale;
use App\Models\Subcategoria;
use App\Models\Stock_producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre','subcategoria_id','precio','imagen','detalle','codigoBarra','descuento'
       
    ];
    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function stockProductos()
    {
        return $this->hasMany(Stock_producto::class);
    }
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }
}
