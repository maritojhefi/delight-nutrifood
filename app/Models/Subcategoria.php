<?php

namespace App\Models;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Adicionale;
use App\Helpers\GlobalHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_id',
        'foto',
        'interacciones'

    ];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    public function adicionales()
    {
        return $this->belongsToMany(Adicionale::class)->withTimestamps()->withPivot('id_grupo');
    }
    public function rutaFoto()
    {
        if ($this->foto == null) {
            return GlobalHelper::getValorAtributoSetting('subcategoria_default');
        } else {
            return "imagenes/subcategorias/" . $this->foto;
        }
    }
}
