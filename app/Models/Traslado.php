<?php

namespace App\Models;

use App\Models\User;
use App\Models\Producto;
use App\Models\Sucursale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Traslado extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','sucursal_id','producto_id','destino_id','cantidad'
       
    ];
  
}
