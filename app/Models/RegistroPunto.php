<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroPunto extends Model
{
    use HasFactory;


    const TIPO_VENTA = 'venta';
    const TIPO_DESCUENTO = 'descuento';
    const TIPO_BONO = 'bono';
    const TIPO_RECOMPENSA = 'recompensa';
    const TIPO_OTRO = 'otro';

    protected $table = 'registros_puntos';
    protected $fillable = [
        'historial_venta_id',
        'partner_id',
        'cliente_id',
        'puntos_partner',
        'puntos_cliente',
        'total_puntos',
        'tipo'
    ];

    public function historialVenta()
    {
        return $this->belongsTo(Historial_venta::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
}
