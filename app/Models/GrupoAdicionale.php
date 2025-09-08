<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoAdicionale extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'maximo_seleccionable',
        'es_obligatorio',
    ];
    // public function adicionales()
    // {
    //     return $this->belongsToMany(Adicionale::class)->withTimestamps();
    // }
    public function adicionales()
    {
        return $this->hasMany(Adicionale::class, 'grupo_adicionales_id');
    }
}
