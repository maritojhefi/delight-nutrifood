<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convenio extends Model
{
    use HasFactory;
    protected $table = "convenios";
    protected $fillable = [
        'nombre_convenio',
        'tipo_descuento',
        'valor_descuento',
        'productos_afectados',
        'fecha_limite',
    ];
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'convenio_user')->withTimestamps();
    }
}
