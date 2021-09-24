<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    const CLIENTE=4;
    const ADMIN=1;
    const INFLUENCER=5;
    const COCINA=3;
    const CAJERO=2;
    protected $fillable = [
        'nombre','descripcion'
       
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
