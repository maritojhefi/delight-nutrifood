<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrada','salida','user_id'
       
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
