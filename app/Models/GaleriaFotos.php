<?php

namespace App\Models;

use App\Helpers\GlobalHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GaleriaFotos extends Model
{
    use HasFactory;
    protected $table="galeria_fotos";
    protected $fillable = [
        'foto','titulo','descripcion','estado'
       
    ];
    
    // Constante para la ruta de fotos de galería en S3
    const RUTA_FOTO = '/imagenes/galeria/';
    
    protected $appends = ['pathFoto'];

    /**
     * Obtener la URL completa de la foto de la galería
     */
    public function getPathFotoAttribute()
    {
        if (empty($this->foto)) {
            return null;
        }
        
        // Usar el disco configurado para generar la URL correcta
        $disk = GlobalHelper::discoArchivos();
        if ($disk === 's3') {
            // Para S3, usar la URL completa
            $config = config('filesystems.disks.s3');
            $bucket = $config['bucket'] ?? '';
            $region = $config['region'] ?? 'us-east-1';
            $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com";
            return $baseUrl . self::RUTA_FOTO . $this->foto;
        } else {
            // Para local, usar asset()
            return asset('imagenes/galeria/' . $this->foto);
        }
    }
}
