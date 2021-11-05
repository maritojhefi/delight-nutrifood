<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almuerzo extends Model
{
    use HasFactory;
    protected $fillable = [
        'dia','ensalada','sopa','ejecutivo','vegetariano','dieta','carbohidrato_1','carbohidrato_2','carbohidrato_3','jugo',
       
    ];
}
