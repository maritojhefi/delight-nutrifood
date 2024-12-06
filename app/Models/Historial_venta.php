<?php

namespace App\Models;

use App\Models\Sucursale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Historial_venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario_id',
        'cliente_id',
        'sucursale_id',
        'total',
        'puntos',
        'descuento',
        'tipo',
        'caja_id',
        'saldo',
        //version 2 atributos para ventas:
        'a_favor_cliente',
        'saldo_monto',
        'total_descuento',
        'descuento_manual',
        'descuento_productos',
        'total_pagado',
        'total_a_pagar',
        'subtotal'
    ];

    public function metodosPagos()
    {
        return $this->belongsToMany(MetodoPago::class)
            ->withPivot('monto', 'id')
            ->withTimestamps();
    }

    public function scopePorVendedor($query, $vendedorId)
    {
        return $query->where('usuario_id', $vendedorId);
    }
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
            ->withPivot('cantidad', 'estado_actual', 'precio_subtotal', 'precio_unitario', 'descuento_producto', 'id')
            ->withTimestamps();
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
}
