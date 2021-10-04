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
        'nombre','precio'
       
    ];
    public function subcategorias()
    {
        return $this->belongsToMany(Subcategoria::class);
    }
}
