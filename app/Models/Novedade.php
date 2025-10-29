<?php

namespace App\Models;

use App\Helpers\GlobalHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Novedade extends Model
{
    use HasFactory;
    protected $fillable = [
        'titulo','contenido','foto'
       
    ];
    
    // Constante para la ruta de fotos de noticias en S3
    const RUTA_FOTO = '/imagenes/noticias/';
    
    protected $appends = ['pathFoto'];
    
    public function getFotoAttribute($value)
    {
        if (empty($value)) {
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
            return $baseUrl . self::RUTA_FOTO . $value;
        } else {
            // Para local, usar la ruta relativa
            return '/imagenes/noticias/'.$value;
        }
    }

    /**
     * Obtener la URL completa de la foto de la noticia
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
            return asset('/imagenes/noticias/' . $this->foto);
        }
    }
}
