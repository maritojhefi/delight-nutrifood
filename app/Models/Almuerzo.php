<?php

namespace App\Models;

use App\Helpers\WhatsappAPIHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Almuerzo extends Model
{
    use HasFactory;
    protected $fillable = [
        'dia',
        'ensalada',
        'sopa',
        'foto',
        'ejecutivo',
        'vegetariano',
        'dieta',
        'carbohidrato_1',
        'carbohidrato_2',
        'carbohidrato_3',
        'jugo',
        'ensalada_cant',
        'sopa_cant',
        'ejecutivo_cant',
        'vegetariano_cant',
        'dieta_cant',
        'carbohidrato_1_cant',
        'carbohidrato_2_cant',
        'carbohidrato_3_cant',
        'jugo_cant',
        'ejecutivo_tiene_carbo',
        'vegetariano_tiene_carbo',
        'dieta_tiene_carbo'
    ];

    public function scopeHoy($query)
    {
        return $query->where('dia', WhatsappAPIHelper::saber_dia(Carbon::today()));
    }
}
