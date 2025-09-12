<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdicionaleGrupo extends Model
{

    protected $table = 'adicionale_grupo'; 

    use HasFactory;

    protected $fillable = [
        'nombre',
        'maximo_seleccionable',
        'es_obligatorio',
    ];

    // Se sostiene una relacion de uno a muchos con la tabla Adicionale
    public function adicionales()
    {
        return $this->hasMany(Adicionale::class, 'adicionale_grupo_id');
    }
}
