<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsappPlanAlmuerzo extends Model
{
    use HasFactory;
    protected $fillable = [
        'cliente_id',
        'cantidad',
        'paso_segundo',
        'paso_carbohidrato',
        'paso_metodo_envio',
        'paso_metodo_empaque',
        'id_plane_user'
        
    ];
    public function cliente()
    {
        return $this->belongsTo(User::class,'cliente_id');
    }

}
