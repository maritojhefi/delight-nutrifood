<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroStockCaja extends Model
{
    use HasFactory;

    protected $fillable = [
        'caja_id',
        'detalle',
        'usuario_id'
    ];
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot('accion', 'cantidad', 'detalle');
    }
}
