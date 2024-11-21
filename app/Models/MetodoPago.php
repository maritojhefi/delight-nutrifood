<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;
    protected $table = 'metodos_pagos';
    protected $fillable = [
        'nombre_metodo_pago','codigo','sucursal_id','descripcion','imagen','activo'
       
    ];

    public function getImagenAttribute($value){
        return asset('images/logo-bancos/'.$value);
    }
}
