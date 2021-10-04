<?php

namespace App\Models;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deshecho extends Model
{
    use HasFactory;
    protected $fillable = [
      
       
    ];
    public function productos()
    {
        return $this->belongsToMany(Producto::class);
    }
}
