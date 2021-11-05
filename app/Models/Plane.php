<?php

namespace App\Models;

use App\Models\User;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plane extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre','producto_id','detalle'
       
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
