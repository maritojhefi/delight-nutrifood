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

    // Settings que se guardan como '1'/'0' y se muestran con un check en el admin
    const OTP_REGISTRO_WHATSAPP = 'otp_registro_whatsapp';
    const ATRIBUTOS_BOOLEANOS = [
        self::OTP_REGISTRO_WHATSAPP => 'Pide un código por WhatsApp para verificar el teléfono al crear una cuenta. Desactivado: la cuenta se crea verificada sin código.',
    ];

    public function getPathImagenAttribute()
    {
        return Storage::disk('public')->url(self::rutaImagen());
    }
    public static function rutaImagen()
    {
        return 'imagenes/' . strtolower(GlobalHelper::getValorAtributoSetting('nombre_sistema')) . '/';
    }
}
