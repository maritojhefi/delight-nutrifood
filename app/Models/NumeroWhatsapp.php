<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroWhatsapp extends Model
{
    use HasFactory;
    protected $table = 'numeros_whatsapps';
    const MODO_IA = 'ia';
    const MODO_MANUAL = 'manual';
    protected $fillable = ['nombre', 'estado', 'numero', 'modo', 'auth_key', 'app_key'];
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}
