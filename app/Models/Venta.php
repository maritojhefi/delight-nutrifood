<?php

namespace App\Models;

use App\Models\User;

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
        'total',
        'puntos',
        'descuento',
        'tipo',
        'despachado_cocina',
        'usuario_manual',
        'impreso',
        'cocina',
        'pagado'
    ];

    public function sucursale()
    {
        return $this->belongsTo(Sucursale::class);
    }
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
            ->withPivot('cantidad', 'estado_actual', 'aceptado', 'adicionales', 'observacion', 'id');
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
}
