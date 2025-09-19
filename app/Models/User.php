<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Plane;
use App\Models\Venta;
use App\Models\Pensione;
use App\Models\Traslado;
use App\Models\Asistencia;
use App\Models\Historial_venta;
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
        'role_id',
        'telf',
        'latitud',
        'longitud',
        'foto',
        'codigo_pais',
        'whatsapp_plan',
        'color_page',
        'profesion',
        'direccion_trabajo',
        'hijos',
        'partner_id',
        'verificado',
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

    protected $appends = ['saldo_formateado'];

    public function getSaldoFormateadoAttribute()
    {
        $saldoAbsoluto = abs($this->saldo);
        return $saldoAbsoluto == floor($saldoAbsoluto) ? number_format($saldoAbsoluto, 0) : number_format($saldoAbsoluto, 2);
    }


    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = Hash::make($value);
    }
    public function setNameAttribute($value)
    {
        $this->attributes["name"] = strtoupper($value);
    }
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }
    public function addCarrito()
    {
        return $this->belongsToMany(Producto::class)
            ->withPivot('cantidad');;
    }
    public function historial_ventas()
    {
        return $this->hasMany(Historial_venta::class, 'cliente_id');
    }
    public function atencionWhatsapp()
    {
        return $this->hasOne(WhatsappPlanAlmuerzo::class, 'cliente_id');
    }
    public function pensione()
    {
        return $this->hasOne(Pensione::class);
    }
    public function dias()
    {
        $fecha1 = Carbon::now();
        $fecha2 = date_create($this->fecha_venc);
        $dias = date_diff($fecha1, $fecha2)->format('%R%a');
        return $dias;
    }

    public function planes()
    {
        return $this->belongsToMany(Plane::class)
            ->withPivot('start', 'end', 'title', 'detalle', 'id', 'estado', 'cocina');
    }
    public function planesPendientes()
    {
        return $this->belongsToMany(Plane::class)
            ->withPivot('start', 'end', 'title', 'detalle', 'id', 'estado')
            ->wherePivot('estado', 'pendiente')
            ->orWherePivot('estado', 'desarrollo');
    }
    public function planesHoy($fecha)
    {
        //dd($this->belongsToMany(Plane::class)->wherePivot('start',$fecha));
        return $this->belongsToMany(Plane::class)->wherePivot('start', $fecha) //->wherePivot('detalle','!=',null)
            ->withPivot('start', 'end', 'title', 'detalle', 'id', 'estado', 'cocina');
    }
    public function planesSemana()
    {
        return $this->belongsToMany(Plane::class)->wherePivotBetween('start', [date("y-m-d", strtotime("last sunday")), date("y-m-d", strtotime("next sunday"))])
            ->withPivot('start', 'end', 'title', 'detalle', 'id', 'cocina');
    }

    public function asistencias()
    {
        return $this->belongsToMany(Contrato::class)->withPivot('entrada', 'salida', 'diferencia_entrada', 'created_at', 'diferencia_salida', 'tiempo_total');
    }
    public function saldos()
    {
        return $this->hasMany(Saldo::class);
    }
    public function saldosVigentes()
    {
        return $this->hasMany(Saldo::class)->whereNull('liquidado')->where('anulado', false)->orderBy('created_at', 'desc');
    }
    public function contrato()
    {
        return $this->hasOne(Contrato::class);
    }
    public function scopeCajeros($query)
    {
        return $query->whereIn('role_id', [1, 2]);
    }
    public function scopeSaldoAFavor($query)
    {
        return $query->where('saldo', '<', 0);
    }
    public function scopeSaldoADeuda($query)
    {
        return $query->where('saldo', '>', 0);
    }
    public function perfilesPuntos()
    {
        return $this->belongsToMany(PerfilPunto::class, 'perfiles_puntos_users', 'user_id', 'perfil_punto_id')->withTimestamps();
    }
    public function convenios()
    {
        return $this->belongsToMany(Convenio::class, 'convenio_user', 'user_id', 'convenio_id')->withTimestamps();
    }
}
