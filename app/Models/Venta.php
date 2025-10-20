<?php

namespace App\Models;

use App\Models\User;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Sucursale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo_entrega',
        'cocina_at',
        'historial_venta_id',
        'usuario_id',
        'cliente_id',
        'sucursale_id',
        'mesa_id',
        'total',
        'puntos',
        'descuento',
        'tipo',
        'despachado_cocina',
        'usuario_manual',
        'impreso',
        'cocina',
        'pagado',
        'reservado_at'
    ];

    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
            ->withPivot('cantidad', 'estado_actual', 'adicionales', 'observacion', 'id', 'descuento_producto', 'descuento_convenio', 'total', 'total_adicionales');
    }
    public function cliente()
    {
        return $this->belongsTo(User::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    public function ventaHistorial()
    {
        return $this->belongsTo(Historial_venta::class, 'historial_venta_id');
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
    public function totalFinal()
    {
        if ($this->ventaHistorial) {
            return $this->ventaHistorial->total_pagado;
        }

        // Total original sin descuentos + adicionales - todos los descuentos
        $sumaDescuentos = $this->productos->sum('pivot.descuento_producto') +
            $this->productos->sum('pivot.descuento_convenio') +
            $this->descuento;

        return $this->total + $this->productos->sum('pivot.total_adicionales') - $sumaDescuentos;
    }
    public function totalItems()
    {
        return $this->productos->sum('pivot.cantidad');
    }
}
