<?php

namespace App\Models;

use App\Helpers\GlobalHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetodoPago extends Model
{
    use HasFactory;
    protected $table = 'metodos_pagos';
    protected $fillable = [
        'nombre_metodo_pago','codigo','sucursal_id','descripcion','imagen','activo'
       
    ];
    
    // Constante para la ruta de imágenes de métodos de pago en S3
    const RUTA_IMAGEN = '/images/logo-bancos/';
    
    protected $appends = ['pathImagen'];

    public function getImagenAttribute($value){
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
            return $baseUrl . self::RUTA_IMAGEN . $value;
        } else {
            // Para local, usar asset()
            return asset('images/logo-bancos/'.$value);
        }
    }

    /**
     * Obtener la URL completa de la imagen del método de pago
     */
    public function getPathImagenAttribute()
    {
        if (empty($this->imagen)) {
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
            return $baseUrl . self::RUTA_IMAGEN . $this->imagen;
        } else {
            // Para local, usar asset()
            return asset('images/logo-bancos/' . $this->imagen);
        }
    }
}
