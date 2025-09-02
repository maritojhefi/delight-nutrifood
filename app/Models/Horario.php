<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'hora_inicio',
        'hora_fin',
        'posicion'
    ];

    public function subcategorias()
    {
        return $this->belongsToMany(Subcategoria::class)->withTimestamps();
    }

    public function getHoraInicioAttribute($value){
        return Carbon::parse($value)->format('H:i');
    }

    public function getHoraFinAttribute($value){
        return Carbon::parse($value)->format('H:i');
    }
}
