<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaDespachoTicket extends Model
{
    use HasFactory;
    protected $table = 'areas_despachos_tickets';
    protected $fillable = [
        'area_despacho_id',
        'ticket_actual',
    ];
    public function areaDespacho()
    {
        return $this->belongsTo(AreaDespacho::class);
    }
}
