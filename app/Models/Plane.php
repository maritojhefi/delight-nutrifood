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
        'nombre','cantidad','dias','producto_id','precio'
       
    ];
    public function usuarios()
    {
        return $this->belongsToMany(User::class)
        ->withPivot('restante','dia_limite');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
        
    }
}
