<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermisoRol extends Model
{
    use HasFactory;
    protected $table = 'permisos_roles';
    protected $fillable = ['role_id', 'modulo', 'ruta'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
