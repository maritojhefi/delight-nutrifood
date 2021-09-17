<?php

namespace App\Models;

use App\Models\Subcategoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre','descripcion'
       
    ];

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class);
    }
}
