<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
