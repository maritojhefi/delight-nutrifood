<?php

namespace App\Models;

use App\Models\User;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plane extends Model
{
    const COLORPERMISO="#A314CD";
    const COLORPENDIENTE="#20C995";
    const COLORFERIADO="#c01222"; 
    const COLORFINALIZADO="#F7843A";
    const COLORARCHIVADO="#B0B5B9";
    const COLORDESARROLLO="#F6CD42";

    const ESTADOPERMISO="permiso";
    const ESTADOPENDIENTE="pendiente";
    const ESTADOFERIADO="feriado";
    const ESTADOFINALIZADO="finalizado";
    const ESTADOARCHIVADO="archivado";
    const ESTADODESARROLLO="desarrollo";

    const ENVIO1="Para Mesa";
    const ENVIO2="Para llevar(Paso a recoger)";
    const ENVIO3="Delivery";

    const COCINAESPERA="espera";
    const COCINADESPACHADO="despachado";
    use HasFactory;
    protected $fillable = [
        'nombre','producto_id','detalle','editable','sopa','segundo','ensalada','carbohidrato','jugo'
       
    ];
    public function usuarios()
    {
        return $this->belongsToMany(User::class)
        ->withPivot('start','end','title');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
        
    }
}
