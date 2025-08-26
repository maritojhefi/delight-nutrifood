<?php

namespace App\Console\Commands\OneRun;

use App\Models\Producto;
use App\Models\Subcategoria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class LimpiezaFotosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'limpiar:fotos-productos-subcategorias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia las fotos de los productos y subcategorias que no existen en el sistema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🧹 Iniciando limpieza de fotos inexistentes...');
        $this->newLine();

        $resultado = $this->limpiarProductos();
        
        $this->newLine();
        $this->info('✅ Proceso completado exitosamente');
        $this->table(
            ['Tipo', 'Total Revisados', 'Limpiados', 'Porcentaje'],
            $resultado['tabla']
        );
        
        $this->newLine();
        $this->line("📊 <fg=green>Resumen total:</>");
        $this->line("   • Productos limpiados: <fg=yellow>{$resultado['productos_limpiados']}</>");
        $this->line("   • Subcategorías limpiadas: <fg=yellow>{$resultado['subcategorias_limpiadas']}</>");
        $this->line("   • Total elementos procesados: <fg=blue>{$resultado['total_procesados']}</>");
        
        return 0;
    }

    public function limpiarProductos()
    {
        // Procesar productos
        $productos = Producto::all();
        $totalProductos = $productos->count();
        $contProductos = 0;
        
        $this->info("🔍 Revisando {$totalProductos} productos...");
        $progressBar = $this->output->createProgressBar($totalProductos);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $progressBar->setMessage('Verificando productos...');
        
        foreach ($productos as $producto) {
            $progressBar->setMessage("Verificando: {$producto->nombre}");
            
            if (!Storage::disk('public_images')->exists($producto->pathAttachment()) && $producto->imagen != null) {
                $producto->imagen = null;
                $producto->save();
                $contProductos++;
                $progressBar->setMessage("✓ Limpiado: {$producto->nombre}");
            }
            
            $progressBar->advance();
            usleep(1000); // Pequeña pausa para visualizar mejor el progreso
        }
        
        $progressBar->finish();
        $this->newLine(2);

        // Procesar subcategorías
        $subcategorias = Subcategoria::all();
        $totalSubcategorias = $subcategorias->count();
        $contSubcategorias = 0;
        
        $this->info("🔍 Revisando {$totalSubcategorias} subcategorías...");
        $progressBar2 = $this->output->createProgressBar($totalSubcategorias);
        $progressBar2->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $progressBar2->setMessage('Verificando subcategorías...');
        
        foreach ($subcategorias as $subcategoria) {
            $progressBar2->setMessage("Verificando: {$subcategoria->nombre}");
            
            if (!Storage::disk('public_images')->exists($subcategoria->rutaFoto()) && $subcategoria->foto != null) {
                $subcategoria->foto = null;
                $subcategoria->save();
                $contSubcategorias++;
                $progressBar2->setMessage("✓ Limpiada: {$subcategoria->nombre}");
            }
            
            $progressBar2->advance();
            usleep(1000); // Pequeña pausa para visualizar mejor el progreso
        }
        
        $progressBar2->finish();
        
        // Calcular porcentajes
        $porcentajeProductos = $totalProductos > 0 ? round(($contProductos / $totalProductos) * 100, 2) : 0;
        $porcentajeSubcategorias = $totalSubcategorias > 0 ? round(($contSubcategorias / $totalSubcategorias) * 100, 2) : 0;
        
        return [
            'productos_limpiados' => $contProductos,
            'subcategorias_limpiadas' => $contSubcategorias,
            'total_procesados' => $totalProductos + $totalSubcategorias,
            'tabla' => [
                ['Productos', $totalProductos, $contProductos, $porcentajeProductos . '%'],
                ['Subcategorías', $totalSubcategorias, $contSubcategorias, $porcentajeSubcategorias . '%']
            ]
        ];
    }
}
