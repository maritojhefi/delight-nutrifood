<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Venta;
use App\Models\Pensione;
use App\Models\Traslado;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'direccion',
        'nacimiento',
        'saldo',
        'puntos',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes["password"]=Hash::make($value);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function pensione()
    {
        return $this->belongsTo(Pensione::class);
    }
   
}
