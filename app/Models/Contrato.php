<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contrato extends Model
{
    use HasFactory;
    protected $fillable = [
      
        'lunes','martes','miercoles','jueves','viernes','sabado','domingo','modalidad','hora_entrada','hora_salida'
        ,'observacion','fecha_inicio','user_id','sueldo'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
