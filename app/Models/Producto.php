<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Combo;
use App\Models\Plane;
use App\Models\Venta;
use App\Models\Deshecho;
use App\Models\Sucursale;
use App\Models\Subcategoria;
use App\Models\Stock_producto;
use App\Models\Historial_venta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{

    use HasFactory;
    protected $fillable = [
        'nombre', 'subcategoria_id', 'precio', 'imagen', 'detalle', 'codigoBarra', 'descuento', 'puntos', 'medicion', 'contable', 'observacion'

    ];

    public function sucursale()
    {

        return $this->belongsToMany(Sucursale::class)
            ->withPivot('sucursale_id', 'cantidad', 'id', 'fecha_venc')
            ->wherePivot('cantidad', '!=', 0);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }
    public function pathAttachment()
    {
        if ($this->imagen == null) {
            return 'imagenes/delight/1.jpeg';
        } else {
            return "imagenes/productos/" . $this->imagen;
        }
    }
    public function detalle()
    {

        return ucfirst(strtolower($this->detalle));
    }
    public function nombre()
    {

        return ucfirst(strtolower($this->nombre));
    }
    public function precio()
    {
        if ($this->descuento != 0 || $this->descuento != null) {
            return floatval($this->descuento);
        } else {
            return  floatval($this->precio);
        }
    }
    public function getPrecioAttribute($value)
    {
        return floatval($value);
    }
    public function getDescuentoAttribute($value)
    {
        return floatval($value);
    }
    public function setPrecioAttribute($value)
    {

        $this->attributes['precio'] = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    public function setMedicionAttribute($value)
    {
        $this->attributes['medicion'] = $this->attributes['precio'] > 1 ? 'unidad' : 'gramo';
    }
    public function addCarrito()
    {
        return $this->belongsToMany(User::class);
    }
    public function ventas()
    {
        return $this->belongsToMany(Venta::class)
            ->withPivot('cantidad', 'adicionales', 'observacion','id','estado_actual');
    }
    public function historialVentas()
    {
        return $this->belongsToMany(Historial_venta::class)
            ->withPivot('cantidad', 'adicionales');
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
    public function plane()
    {
        return $this->hasOne(Plane::class);
    }
}
