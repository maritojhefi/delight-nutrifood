<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoAdicionales extends Model
{
    protected $table = 'grupo_adicionales'; 

    use HasFactory;

    protected $fillable = [
        'nombre',
        'es_obligatorio',
        'maximo_seleccionable',
    ];
}
