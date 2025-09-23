<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoAdicionales extends Model
{
    protected $table = 'grupos_adicionales';

    use HasFactory;

    protected $fillable = [
        'nombre_grupo',
        'es_obligatorio',
        'maximo_seleccionable',
        'subcategoria_id',
    ];

    // Relación para obtener los adicionales que pertenecen a este grupo en una subcategoría específica
    public function adicionalesEnSubcategoria()
    {
        return $this->belongsToMany(Adicionale::class, 'adicionale_subcategoria', 'grupo_adicionales_id', 'adicionale_id')
            ->withPivot('subcategoria_id');
    }
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }
}
