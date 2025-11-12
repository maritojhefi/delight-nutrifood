<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;
    protected $fillable = [

        'caja_id',
        'detalle',
        'monto',
        'metodo_pago_id'
    ];
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class);
    }
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
}
