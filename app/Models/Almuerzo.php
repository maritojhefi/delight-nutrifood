<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Almuerzo extends Model
{
    use HasFactory;
    protected $fillable = [
        'dia',
        'estado_dia',
        'ensalada',
        'sopa',
        'foto',
        'ejecutivo',
        'vegetariano',
        'dieta',
        'carbohidrato_1',
        'carbohidrato_2',
        'carbohidrato_3',
        'carbohidrato_1_estado',
        'carbohidrato_2_estado',
        'carbohidrato_3_estado',
        'ejecutivo_estado',
        'dieta_estado',
        'vegetariano_estado',
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
    protected static function boot()
    {
        parent::boot();

        // Agregar el Global Scope
        static::addGlobalScope('diasActivos', function (Builder $builder) {
            $builder->where('estado_dia', true);
        });
    }
    public function scopeHoy($query)
    {
        return $query->where('dia', WhatsappAPIHelper::saber_dia(Carbon::today()));
    }
}
