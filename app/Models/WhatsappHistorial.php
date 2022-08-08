<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappHistorial extends Model
{
    use HasFactory;
    protected $fillable = [
        'destino','contenido','template','tipo'
       
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class,'destino','telf');
    }
}
