<?php

namespace App\Models;

use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adicionale extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio',
        'cantidad',
        'contable',
        'codigo_cocina'

    ];
    public function subcategorias()
    {
        return $this->belongsToMany(Subcategoria::class);
    }
    public function scopeCocinaAdicionales($query)
    {
        return $query->where('codigo_cocina', '!=', null);
    }
}
