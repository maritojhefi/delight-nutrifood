<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_mesa',
        'numero',
        'url',
        'codigo',
    ];
    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function venta()
    {
        return $this->hasOne(Venta::class);
    }
}
