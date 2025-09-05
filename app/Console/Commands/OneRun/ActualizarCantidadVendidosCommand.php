<?php

namespace App\Console\Commands\OneRun;

use App\Models\Producto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ActualizarCantidadVendidosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actualizar:cantidad-ventas-productos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el atributo "cantidad_vendida" de los registros en la tabla "Productos" para reflejar
    los valores existentes en historial_venta_producto ';

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
        $this->info('Iniciando actualizacion de Productos[cantidad_vendida]...');
        $this->newLine();

        $result = $this->actualizarCantidadVendidos();
        // $result = $this->actualizarCantidadVendidosRaw();

        return $result;
    }

    public function actualizarCantidadVendidos() {
        // Ejectua logica del modelo
        try {
            $productos = Producto::all();
            $totalProductos = $productos->count();
            
            $this->info("📊 Revisando {$totalProductos} productos...");
            
            $bar = $this->output->createProgressBar($totalProductos);
            $bar->start();
            
            
            foreach ($productos as $producto) {
                $totalVendido = $producto->historialVentas()->sum('cantidad');
                // Actualizar solo si el valor es distinto
                if ($producto->cantidad_vendida !== $totalVendido) {
                    $producto->cantidad_vendida = $totalVendido;
                    $producto->save();
                }

                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            $this->info('✅ Proceso completado exitosamente');
            
        } catch (\Throwable $th) {
            $this->error("Ocurrió un error al tratar de actualizar los productos: " . $th->getMessage());
            return 1;
        }
        
        return 0;
    }

    public function actualizarCantidadVendidosRaw()
    {
        // No ejecuta logica del modelo
        // (Como la actualizacion de cache para busqueda de productos).
        try {
            $this->info('🚀 Usando método optimizado con SQL...');
            
            $affectedRows =  DB::statement("
                UPDATE productos 
                SET cantidad_vendida = (
                    SELECT COALESCE(SUM(cantidad), 0) 
                    FROM historial_venta_producto 
                    WHERE producto_id = productos.id
                )
            ");

            $this->info("✅ Proceso completado. Registros procesados usando SQL optimizado.");
            return 0;

        } catch (\Throwable $th) {
            $this->error("❌ Error en método SQL: " . $th->getMessage());
            return 1;
        }
    }
}
