<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almuerzo extends Model
{
    use HasFactory;
    protected $fillable = [
        'dia','ensalada','sopa','foto','ejecutivo','vegetariano','dieta','carbohidrato_1','carbohidrato_2','carbohidrato_3','jugo',
        'ensalada_cant','sopa_cant','ejecutivo_cant','vegetariano_cant','dieta_cant','carbohidrato_1_cant','carbohidrato_2_cant','carbohidrato_3_cant','jugo_cant',
    ];

    
}
