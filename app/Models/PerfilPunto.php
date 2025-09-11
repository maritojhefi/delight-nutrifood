<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilPunto extends Model
{
    use HasFactory;
    protected $table = 'perfiles_puntos';
    const CODIGO_PATROCINADOR = "REF";

    protected $fillable = [
        'nombre',
        'porcentaje',
        'bono'
    ];
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucfirst($value);
    }
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'perfiles_puntos_users', 'perfil_punto_id', 'user_id')->withTimestamps();
    }
}
