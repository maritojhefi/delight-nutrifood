<?php

namespace App\Console\Commands\OneRun;

use App\Models\User;
use App\Models\Producto;
use App\Models\Subcategoria;
use App\Models\Almuerzo;
use App\Models\Novedade;
use App\Models\GaleriaFotos;
use App\Models\MetodoPago;
use App\Models\Setting;
use App\Helpers\ProcesarImagen;
use App\Helpers\GlobalHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Exception;

class MigrarMultimediaAS3Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:multimedia-s3 {--dry-run : Ejecutar sin hacer cambios reales} {--process-images : Procesar y comprimir imágenes durante la migración} {--only-existing : Migrar solo archivos que existen físicamente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra archivos multimedia existentes de public/imagenes a S3';

    /**
     * Configuración de modelos y sus campos de imagen
     * Utiliza las constantes definidas en cada modelo para mantener consistencia
     */
    private $modelosConfig = [
        'User' => [
            'model' => User::class,
            'campo' => 'foto',
            'ruta_local' => 'imagenes/perfil/',
            'ruta_s3' => User::RUTA_FOTO, // Usa la constante del modelo
        ],
        'Producto' => [
            'model' => Producto::class,
            'campo' => 'imagen',
            'ruta_local' => 'imagenes/productos/',
            'ruta_s3' => Producto::RUTA_IMAGENES, // Usa la constante del modelo
        ],
        'Subcategoria' => [
            'model' => Subcategoria::class,
            'campo' => 'foto',
            'ruta_local' => 'imagenes/subcategorias/',
            'ruta_s3' => Subcategoria::RUTA_FOTO, // Usa la constante del modelo
        ],
        'Almuerzo' => [
            'model' => Almuerzo::class,
            'campo' => 'foto',
            'ruta_local' => 'imagenes/almuerzo/',
            'ruta_s3' => Almuerzo::RUTA_FOTO, // Usa la constante del modelo
        ],
        'Novedade' => [
            'model' => Novedade::class,
            'campo' => 'foto',
            'ruta_local' => 'imagenes/noticias/',
            'ruta_s3' => Novedade::RUTA_FOTO, // Usa la constante del modelo
        ],
        'GaleriaFotos' => [
            'model' => GaleriaFotos::class,
            'campo' => 'foto',
            'ruta_local' => 'imagenes/galeria/',
            'ruta_s3' => GaleriaFotos::RUTA_FOTO, // Usa la constante del modelo
        ],
        'MetodoPago' => [
            'model' => MetodoPago::class,
            'campo' => 'imagen',
            'ruta_local' => 'images/logo-bancos/',
            'ruta_s3' => MetodoPago::RUTA_IMAGEN, // Usa la constante del modelo
        ],
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🚀 Iniciando migración de archivos multimedia a S3...');

        // Mostrar información sobre el modo de ejecución
        if ($this->option('dry-run')) {
            $this->warn('⚠️  MODO DRY-RUN: No se realizarán cambios reales');
        }

        if ($this->option('process-images')) {
            $this->info('🖼️  MODO PROCESAMIENTO: Las imágenes serán comprimidas y redimensionadas');
        } else {
            $this->info('📁 MODO MIGRACIÓN DIRECTA: Los archivos se migrarán tal como están');
        }

        if ($this->option('only-existing')) {
            $this->info('📂 MODO SOLO EXISTENTES: Solo se migrarán archivos que existen físicamente');
        }

        // Verificar configuración S3
        if (!$this->verificarConfiguracionS3()) {
            return 1;
        }

        $totalMigrados = 0;
        $totalErrores = 0;

        // Procesar cada modelo configurado
        foreach ($this->modelosConfig as $nombreModelo => $config) {
            $this->info("\n📁 Procesando modelo: {$nombreModelo}");

            try {
                $resultado = $this->migrarModelo($config, $nombreModelo);
                $totalMigrados += $resultado['migrados'];
                $totalErrores += $resultado['errores'];

                $this->info("✅ {$nombreModelo}: {$resultado['migrados']} migrados, {$resultado['errores']} errores");
            } catch (Exception $e) {
                $this->error("❌ Error procesando {$nombreModelo}: " . $e->getMessage());
                Log::error("Error migrando {$nombreModelo}", ['error' => $e->getMessage()]);
                $totalErrores++;
            }
        }

        // Migrar archivos de configuración del sistema
        // $this->migrarArchivosSistema();

        // Mostrar resumen final
        $this->info("\n🎉 Migración completada!");
        $this->info("📊 Total migrados: {$totalMigrados}");
        $this->info("❌ Total errores: {$totalErrores}");

        if ($totalErrores > 0) {
            $this->warn('⚠️  Revisa los logs para más detalles sobre los errores');
        }

        // Mostrar información de URLs S3
        if ($totalMigrados > 0) {
            $this->info("\n🔗 Información de URLs S3:");
            $config = config('filesystems.disks.s3');
            $bucket = $config['bucket'] ?? '';
            $region = $config['region'] ?? 'us-east-1';
            $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com";
            
            $this->line("   📦 Bucket: {$bucket}");
            $this->line("   🌍 Región: {$region}");
            $this->line("   🔗 URL Base: {$baseUrl}");
            $this->line("   📁 Estructura: /imagenes/{tipo}/{archivo}");
        }

        return 0;
    }

    /**
     * Verificar que la configuración S3 esté correcta
     */
    private function verificarConfiguracionS3(): bool
    {
        $this->info('🔍 Verificando configuración S3...');

        $configuraciones = [
            'AWS_ACCESS_KEY_ID' => env('AWS_ACCESS_KEY_ID'),
            'AWS_SECRET_ACCESS_KEY' => env('AWS_SECRET_ACCESS_KEY'),
            'AWS_DEFAULT_REGION' => env('AWS_DEFAULT_REGION'),
            'AWS_BUCKET' => env('AWS_BUCKET'),
            // 'AWS_USE_PATH_STYLE_ENDPOINT' => env('AWS_USE_PATH_STYLE_ENDPOINT'),
        ];

        foreach ($configuraciones as $key => $value) {
            if (empty($value)) {
                $this->error("❌ Falta configuración: {$key}");
                return false;
            }
        }

        // Probar conexión S3
        try {
            Storage::disk('s3')->exists('test-connection.txt');
            $this->info('✅ Conexión S3 verificada');
            return true;
        } catch (Exception $e) {
            $this->error('❌ Error conectando a S3: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Migrar archivos de un modelo específico usando el helper ProcesarImagen
     */
    private function migrarModelo(array $config, string $nombreModelo): array
    {
        $modelo = $config['model'];
        $campo = $config['campo'];
        $rutaLocal = $config['ruta_local'];
        $rutaS3 = $config['ruta_s3'];

        // Obtener todos los registros que tienen imágenes
        $registros = $modelo::whereNotNull($campo)->get();
        $migrados = 0;
        $errores = 0;

        $this->info("   📋 Encontrados {$registros->count()} registros con {$campo}");

        foreach ($registros as $registro) {
            // Obtener el valor original del campo sin pasar por accessors
            // Esto es importante porque algunos modelos tienen accessors que convierten
            // el nombre del archivo en URLs completas (ej: MetodoPago)
            $nombreArchivo = $registro->getOriginal($campo);

            if (empty($nombreArchivo)) {
                continue;
            }

            $rutaArchivoLocal = public_path($rutaLocal . $nombreArchivo);
            $rutaArchivoS3 = $rutaS3 . $nombreArchivo;

            // Verificar si el archivo existe localmente
            if (!file_exists($rutaArchivoLocal)) {
                if ($this->option('only-existing')) {
                    // En modo solo existentes, omitir archivos que no existen
                    continue;
                } else {
                    $this->warn("   ⚠️  Archivo no encontrado: {$rutaArchivoLocal}");
                    $errores++;
                    continue;
                }
            }

            try {
                if (!$this->option('dry-run')) {
                    // Determinar si procesar la imagen o migrarla directamente
                    if ($this->option('process-images')) {
                        // Procesar imagen usando el helper ProcesarImagen
                        $archivoProcesado = $this->procesarImagenParaMigracion($rutaArchivoLocal, $rutaS3, $nombreArchivo);
                        
                        if ($archivoProcesado) {
                            // NO actualizar la base de datos - debe mantener solo el nombre del archivo
                            // La URL completa se maneja en los modelos usando las constantes
                            $this->line("   ✅ Procesado y migrado: {$nombreArchivo}");
                        } else {
                            $this->error("   ❌ Error procesando imagen: {$nombreArchivo}");
                            $errores++;
                            continue;
                        }
                    } else {
                        // Migrar archivo directamente sin procesamiento
                        $contenido = file_get_contents($rutaArchivoLocal);
                        Storage::disk('s3')->put($rutaArchivoS3, $contenido, 'public');
                        
                        // NO actualizar la base de datos - debe mantener solo el nombre del archivo
                        // La URL completa se maneja en los modelos usando las constantes
                        $this->line("   ✅ Migrado: {$nombreArchivo}");
                    }
                    
                    // Generar URL del archivo en S3
                    $config = config('filesystems.disks.s3');
                    $bucket = $config['bucket'] ?? '';
                    $region = $config['region'] ?? 'us-east-1';
                    $urlS3 = "https://{$bucket}.s3.{$region}.amazonaws.com{$rutaArchivoS3}";
                    
                    $this->line("Ruta S3: {$rutaArchivoS3}");
                    $this->line("URL: {$urlS3}");
                } else {
                    // En modo dry-run, mostrar qué se haría
                    $config = config('filesystems.disks.s3');
                    $bucket = $config['bucket'] ?? '';
                    $region = $config['region'] ?? 'us-east-1';
                    $urlS3 = "https://{$bucket}.s3.{$region}.amazonaws.com{$rutaArchivoS3}";
                    
                    if ($this->option('process-images')) {
                        $this->line("   🔍 [DRY-RUN] Procesaría y migraría: {$nombreArchivo}");
                    } else {
                        $this->line("   🔍 [DRY-RUN] Migraría: {$nombreArchivo}");
                    }
                    $this->line("[DRY-RUN] Ruta S3: {$rutaArchivoS3}");
                    $this->line("[DRY-RUN] URL: {$urlS3}");
                }

                $migrados++;
            } catch (Exception $e) {
                $this->error("   ❌ Error migrando {$nombreArchivo}: " . $e->getMessage());
                Log::error('Error migrando archivo', [
                    'modelo' => $nombreModelo,
                    'archivo' => $nombreArchivo,
                    'error' => $e->getMessage(),
                ]);
                $errores++;
            }
        }

        return ['migrados' => $migrados, 'errores' => $errores];
    }

    /**
     * Procesar imagen para migración usando el helper ProcesarImagen
     * 
     * @param string $rutaArchivoLocal Ruta local del archivo
     * @param string $rutaS3 Ruta de destino en S3
     * @param string $nombreArchivo Nombre del archivo
     * @return bool True si se procesó correctamente, false en caso contrario
     */
    private function procesarImagenParaMigracion(string $rutaArchivoLocal, string $rutaS3, string $nombreArchivo): bool
    {
        try {
            // Crear un objeto UploadedFile simulado para usar con ProcesarImagen
            $uploadedFile = new UploadedFile(
                $rutaArchivoLocal,
                $nombreArchivo,
                mime_content_type($rutaArchivoLocal),
                null,
                true
            );

            // Obtener la extensión del archivo original
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nombreSinExtension = pathinfo($nombreArchivo, PATHINFO_FILENAME);

            // Configurar el procesamiento de imagen
            $procesarImagen = ProcesarImagen::crear($uploadedFile)
                ->carpeta(ltrim($rutaS3, '/')) // Remover la barra inicial para la carpeta
                ->nombreArchivo($nombreSinExtension)
                ->formato($extension);

            // Aplicar redimensionado estándar para optimización
            $procesarImagen->dimensiones(800, null); // Máximo 800px de ancho, mantener proporción

            // Guardar la imagen procesada directamente en S3
            $nombreArchivoProcesado = $procesarImagen->guardar();

            return !empty($nombreArchivoProcesado);
        } catch (Exception $e) {
            Log::error('Error procesando imagen para migración', [
                'archivo' => $nombreArchivo,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Migrar archivos del sistema (delight, etc.)
     * Estos archivos se migran directamente sin procesamiento ya que son archivos del sistema
     */
    private function migrarArchivosSistema(): void
    {
        $this->info("\n📁 Migrando archivos del sistema...");

        // Directorios del sistema que no están asociados a modelos específicos
        $directoriosSistema = ['imagenes/delight/', 'imagenes/noticias/', 'imagenes/galeria/'];

        foreach ($directoriosSistema as $directorio) {
            $rutaCompleta = public_path($directorio);

            if (!is_dir($rutaCompleta)) {
                continue;
            }

            $archivos = glob($rutaCompleta . '*');
            $migrados = 0;

            foreach ($archivos as $archivo) {
                if (is_file($archivo)) {
                    $nombreArchivo = basename($archivo);
                    $rutaS3 = '/' . $directorio . $nombreArchivo;

                    try {
                        if (!$this->option('dry-run')) {
                            // Para archivos del sistema, migrar directamente sin procesamiento
                            $contenido = file_get_contents($archivo);
                            Storage::disk('s3')->put($rutaS3, $contenido, 'public');
                            
                            // Generar URL del archivo en S3
                            $config = config('filesystems.disks.s3');
                            $bucket = $config['bucket'] ?? '';
                            $region = $config['region'] ?? 'us-east-1';
                            $urlS3 = "https://{$bucket}.s3.{$region}.amazonaws.com{$rutaS3}";
                            
                            $this->line("   ✅ Sistema: {$nombreArchivo}");
                            $this->line("   📍 Ruta S3: {$rutaS3}");
                            $this->line("   🔗 URL: {$urlS3}");
                        } else {
                            // En modo dry-run, mostrar qué se haría
                            $config = config('filesystems.disks.s3');
                            $bucket = $config['bucket'] ?? '';
                            $region = $config['region'] ?? 'us-east-1';
                            $urlS3 = "https://{$bucket}.s3.{$region}.amazonaws.com{$rutaS3}";
                            
                            $this->line("   🔍 [DRY-RUN] Sistema: {$nombreArchivo}");
                            $this->line("   📍 [DRY-RUN] Ruta S3: {$rutaS3}");
                            $this->line("   🔗 [DRY-RUN] URL: {$urlS3}");
                        }

                        $migrados++;
                    } catch (Exception $e) {
                        $this->error("   ❌ Error: {$nombreArchivo} - " . $e->getMessage());
                    }
                }
            }

            $this->info("   📊 {$directorio}: {$migrados} archivos migrados");
        }
    }
}
