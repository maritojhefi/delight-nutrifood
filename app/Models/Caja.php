<?php

namespace App\Models;

use App\Models\Sucursale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = [
        'acumulado', 'entrada', 'sucursale_id', 'estado'

    ];
    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function ventas()
    {
        return $this->hasMany(Historial_venta::class);
    }
    public function saldos()
    {
        return $this->hasMany(Saldo::class, 'caja_id');
    }
    public function saldosPagadosSinVenta()
    {
        return $this->hasMany(Saldo::class, 'caja_id')->where('historial_ventas_id', null)->where('es_deuda', false)->where('anulado', false);
    }
}
