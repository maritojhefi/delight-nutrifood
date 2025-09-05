<?php

namespace App\Models;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'icono',
    ];
    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withTimestamps();
    }

    public function producto()
    {
        return $this->belongsToMany(Producto::class)
            ->withTimestamps()
            ->withPivot('tag_id', 'producto_id');
    }
    public function scopeTieneProductosDisponibles($query)
    {
        // Make sure we're using the correct relationship name and scope
        return $query->whereHas('productos', function ($query) {
            $query->publicoTienda();
        });
    }
}
