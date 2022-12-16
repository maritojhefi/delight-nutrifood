<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciboImpreso extends Model
{
    use HasFactory;
    protected $fillable = [
        'venta_id', 'historial_venta_id', 'observacion', 'cliente', 'telefono','fecha','metodo'

    ];
}
