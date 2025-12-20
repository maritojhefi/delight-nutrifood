<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaDespacho extends Model
{
    use HasFactory;
    protected $table = 'areas_despachos';
    protected $fillable = [
        'nombre_area',
        'codigo_area',
        'descripcion',
        'activo',
        'sucursale_id',
        'id_impresora',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
    public function tickets()
    {
        return $this->hasMany(AreaDespachoTicket::class);
    }
}
