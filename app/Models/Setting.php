<?php

namespace App\Models;

use App\Helpers\GlobalHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $fillable = ['atributo', 'valor', 'es_imagen'];

    protected $appends = ['pathImagen'];

    public function getPathImagenAttribute()
    {
        return Storage::disk('public')->url(self::rutaImagen());
    }
    public static function rutaImagen()
    {
        return 'imagenes/' . strtolower(GlobalHelper::getValorAtributoSetting('nombre_sistema')) . '/';
    }
}
