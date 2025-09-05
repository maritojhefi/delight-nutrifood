<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Models\Historial_venta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalcularPuntosClientesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientes:recalcular-puntos {--dry-run : Ejecutar en modo de prueba sin guardar cambios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalcular puntos de los clientes de acuerdo a sus registros de ventas';

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
        $isDryRun = $this->option('dry-run');
        
        $this->info('Iniciando recálculo de puntos de clientes...');
        if ($isDryRun) {
            $this->warn('MODO DE PRUEBA ACTIVADO - No se guardarán cambios');
        }
        
        // Obtener estadísticas iniciales
        $totalClientes = User::where('role_id', Role::CLIENTE)->count();
        
        if ($totalClientes === 0) {
            $this->warn('No se encontraron clientes para procesar');
            return 0;
        }

        // Obtener clientes con sus historiales
        $clientes = User::where('role_id', Role::CLIENTE)
            ->with(['historial_ventas' => function($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->get();

        // Variables para estadísticas
        $clientesProcesados = 0;
        $clientesConPuntos = 0;
        $clientesSinHistorial = 0;
        $totalPuntosAsignados = 0;
        $historialesProcesados = 0;
        $cambiosRealizados = [];

        // Crear barra de progreso
        $progressBar = $this->output->createProgressBar($clientes->count());
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
        $progressBar->start();

        DB::beginTransaction();
        
        try {
            foreach ($clientes as $cliente) {
                // Guardar puntos originales antes del recálculo
                $puntosOriginales = $cliente->puntos;
                
                // Resetear puntos del cliente
                if (!$isDryRun) {
                    $cliente->puntos = 0;
                    $cliente->save();
                }

                $historiales = $cliente->historial_ventas;
                $puntosAcumulados = 0;

                if ($historiales->isEmpty()) {
                    $clientesSinHistorial++;
                } else {
                    foreach ($historiales as $historial) {
                        $puntosVenta = $historial->puntos;
                        
                        if (!$isDryRun) {
                            $historial->puntos = $puntosVenta;
                            $historial->save();
                        }
                        
                        $puntosAcumulados += $puntosVenta;
                        $historialesProcesados++;
                    }
                    
                    // Asignar puntos acumulados al cliente
                    if (!$isDryRun) {
                        $cliente->puntos = $puntosAcumulados;
                        $cliente->save();
                    }
                    
                    $totalPuntosAsignados += $puntosAcumulados;
                    
                    if ($puntosAcumulados > 0) {
                        $clientesConPuntos++;
                    }
                }

                // Solo registrar cambios si hubo diferencia en los puntos
                if ($puntosOriginales != $puntosAcumulados) {
                    $cambiosRealizados[] = [
                        'id' => $cliente->id,
                        'nombre' => $cliente->name,
                        'puntos_originales' => $puntosOriginales,
                        'puntos_nuevos' => $puntosAcumulados,
                        'diferencia' => $puntosAcumulados - $puntosOriginales
                    ];
                }

                $clientesProcesados++;
                $progressBar->advance();
            }

            if (!$isDryRun) {
                DB::commit();
            } else {
                DB::rollback();
            }

        } catch (\Exception $e) {
            DB::rollback();
            $this->error("\nError durante el procesamiento: " . $e->getMessage());
            $progressBar->finish();
            return 1;
        }

        $progressBar->finish();

        // Mostrar resumen
        $this->newLine(2);
        $this->info('RESUMEN DEL PROCESO:');
        $this->line("   Clientes procesados: {$clientesProcesados}");
        $this->line("   Clientes con puntos: {$clientesConPuntos}");
        $this->line("   Clientes sin historial: {$clientesSinHistorial}");
        $this->line("   Historiales procesados: {$historialesProcesados}");
        $this->line("   Total de puntos asignados: {$totalPuntosAsignados}");
        $this->line("   Clientes con cambios: " . count($cambiosRealizados));
        
        // Mostrar solo los cambios realizados
        if (!empty($cambiosRealizados)) {
            $this->newLine();
            $this->info('CAMBIOS REALIZADOS:');
            $this->line('ID | Nombre | Puntos Anteriores | Puntos Nuevos | Diferencia');
            $this->line('---|--------|-------------------|---------------|-----------');
            
            foreach ($cambiosRealizados as $cambio) {
                $diferencia = $cambio['diferencia'] > 0 ? '+' . $cambio['diferencia'] : (string)$cambio['diferencia'];
                $this->line(sprintf(
                    '%2d | %-20s | %15d | %13d | %9s',
                    $cambio['id'],
                    substr($cambio['nombre'], 0, 20),
                    $cambio['puntos_originales'],
                    $cambio['puntos_nuevos'],
                    $diferencia
                ));
            }
        } else {
            $this->newLine();
            $this->info('No se realizaron cambios en los puntos de ningún cliente.');
        }
        
        if ($isDryRun) {
            $this->warn("\nRECUERDA: Este fue un modo de prueba. Ejecuta sin --dry-run para aplicar los cambios.");
        } else {
            $this->info("\nRecalculo de puntos completado exitosamente!");
        }

        return 0;
    }
}
