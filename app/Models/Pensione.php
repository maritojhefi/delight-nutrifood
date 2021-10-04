<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pensione extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','fecha_venc'
       
    ];
    public function usuario()
    {
        return $this->hasOne(User::class);
    }
}
