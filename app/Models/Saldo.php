<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $fillable = [
        'historial_venta_id',
        'user_id',
        'monto',
        'es_deuda',
        'anulado',
        'detalle',
        'caja_id',
        'historial_ventas_id',
        'atendido_por',
        'tipo',
        'liquidado',
        'saldo_restante'

    ];
    protected $appends = ['saldo_restante_formateado'];
    public function getSaldoRestanteFormateadoAttribute()
    {
        $saldoRestante = abs($this->saldo_restante);
        return $saldoRestante == floor($saldoRestante) ? number_format($saldoRestante, 0) : number_format($saldoRestante, 2);
    }
    // public function getMontoAttribute($value)
    // {
    //     $monto = abs($value);
    //     return $monto == floor($monto) ? number_format($monto, 0) : number_format($monto, 2);
    // }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function venta()
    {
        return $this->belongsTo(Historial_venta::class, 'historial_ventas_id');
    }
    public function venta2()
    {
        return $this->belongsTo(Historial_venta::class, 'historial_venta_id');
    }
    public function atendidoPor()
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }
    public function metodosPagos()
    {
        return $this->belongsToMany(MetodoPago::class)->withTimestamps()->withPivot('monto');
    }
}
