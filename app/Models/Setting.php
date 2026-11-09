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

    // Porcentaje (0-100) del total de la venta que se puede descontar a mano en el POS. Aplica a todos los roles.
    const DESCUENTO_MANUAL_MAXIMO = 'descuento_manual_maximo_porcentaje';

    // Descripciones de settings de texto que se muestran en Configuraciones > Sistema
    const ATRIBUTOS_DESCRIPCION = [
        self::DESCUENTO_MANUAL_MAXIMO => 'Porcentaje máximo (0 a 100) del total de la venta que se puede poner como descuento manual en el punto de venta. Aplica a todos los roles, incluido el admin.',
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
