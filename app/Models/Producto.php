<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Combo;
use App\Models\Venta;
use App\Models\Deshecho;
use App\Models\Sucursale;
use App\Models\Subcategoria;
use App\Models\Stock_producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre','subcategoria_id','precio','imagen','detalle','codigoBarra','descuento','puntos','medicion'
       
    ];
    public function sucursale()
    {
        return $this->belongsToMany(Sucursale::class);
    }
   
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }
    public function pathAttachment(){
        return "imagenes/productos/".$this->imagen;
    }
    public function ventas()
    {
        return $this->belongsToMany(Venta::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function combos()
    {
        return $this->belongsToMany(Combo::class);
    }
    public function deshechos()
    {
        return $this->belongsToMany(Deshecho::class);
    }
}
