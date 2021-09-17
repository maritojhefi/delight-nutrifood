<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    const CLIENTE=1;
    const ADMIN=2;
    const MESERO=3;
    const COCINA=4;
    const PANADERO=5;
    protected $fillable = [
        'nombre','descripcion'
       
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
