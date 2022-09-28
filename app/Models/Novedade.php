<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novedade extends Model
{
    use HasFactory;
    protected $fillable = [
        'titulo','contenido','foto'
       
    ];
    public function getFotoAttribute($value)
    {
        return 'imagenes/noticias/'.$value;
    }
}
