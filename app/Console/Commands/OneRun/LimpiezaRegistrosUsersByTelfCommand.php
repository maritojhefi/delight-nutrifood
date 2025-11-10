<?php

namespace App\Console\Commands\OneRun;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LimpiezaRegistrosUsersByTelfCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'limpiar:usuarios-telefonos-duplicados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia registros duplicados de usuarios basándose en el campo teléfono';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('==============================================');
        $this->info('  Análisis de Usuarios Duplicados por Teléfono');
        $this->info('==============================================');
        $this->newLine();

        // Obtener teléfonos duplicados
        $telefonosDuplicados = DB::table('users')
            ->select('telf', DB::raw('COUNT(*) as total'))
            ->whereNotNull('telf')
            ->where('telf', '!=', '')
            ->groupBy('telf')
            ->having('total', '>', 1)
            ->pluck('telf');

        if ($telefonosDuplicados->isEmpty()) {
            $this->info('✓ No se encontraron registros duplicados.');
            return Command::SUCCESS;
        }

        $this->warn("⚠ Se encontraron {$telefonosDuplicados->count()} teléfonos con registros duplicados.");
        $this->newLine();

        // Obtener todos los usuarios duplicados ordenados
        $usuariosDuplicados = User::whereIn('telf', $telefonosDuplicados)
            ->orderBy('name')
            ->orderBy('created_at')
            ->get();

        // Preparar datos para la tabla
        $datosTabla = [];
        foreach ($usuariosDuplicados as $user) {
            $datosTabla[] = [
                'ID' => $user->id,
                'Duplicado' => $user->telf ?? 'N/A',
                'Nombre' => $user->name ?? 'Sin nombre',
                'Cant Ventas' => $this->obtenerCantidadVentas($user->id),
                'Creación' => $user->created_at ? $user->created_at->format('d-m-Y') : 'N/A',
                'Saldo' => number_format($this->obtenerSaldo($user->id), 2),
                'Correo' => $user->email ?? 'Sin correo',
            ];
        }

        // Mostrar tabla de duplicados
        $this->table(
            ['ID', 'Duplicado', 'Nombre', 'Cant Ventas', 'Creación', 'Saldo', 'Correo'],
            $datosTabla
        );

        $this->newLine();
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->warn('  IMPORTANTE: Revisa cuidadosamente antes de eliminar');
        $this->info('  Presiona Ctrl+C para salir en cualquier momento');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        // Bucle infinito para eliminar usuarios
        while (true) {
            $idEliminar = $this->ask('📝 Ingresa el ID del usuario a eliminar (o Ctrl+C para salir)');

            // Validar entrada
            if (empty($idEliminar) || !is_numeric($idEliminar)) {
                $this->error('❌ Debes ingresar un ID numérico válido.');
                $this->newLine();
                continue;
            }

            $idEliminar = (int) $idEliminar;

            // Validar que el ID exista en los duplicados
            $usuario = User::find($idEliminar);

            if (!$usuario) {
                $this->error("❌ No se encontró ningún usuario con el ID: {$idEliminar}");
                $this->newLine();
                continue;
            }

            // Verificar que el usuario esté en la lista de duplicados
            $esDuplicado = $usuariosDuplicados->contains('id', $idEliminar);
            
            if (!$esDuplicado) {
                $this->error("❌ El usuario con ID {$idEliminar} no está en la lista de duplicados.");
                $this->newLine();
                continue;
            }

            // Verificar relaciones antes de eliminar
            $relacionesInfo = $this->verificarRelaciones($idEliminar);

            // Mostrar información del usuario a eliminar
            $this->newLine();
            $this->line('┌─────────────────────────────────────────────┐');
            $this->line('│  INFORMACIÓN DEL USUARIO A ELIMINAR         │');
            $this->line('├─────────────────────────────────────────────┤');
            $this->line("│  ID:       {$usuario->id}");
            $this->line("│  Nombre:   {$usuario->name}");
            $this->line("│  Teléfono: {$usuario->telf}");
            $this->line("│  Correo:   {$usuario->email}");
            $this->line("│  Creado:   {$usuario->created_at->format('d-m-Y H:i:s')}");
            $this->line('├─────────────────────────────────────────────┤');
            $this->line('│  RELACIONES ENCONTRADAS:                    │');
            $this->line("│  • Historial Ventas: {$relacionesInfo['historial_ventas']}");
            $this->line("│  • Productos Vendidos: {$relacionesInfo['productos_vendidos']}");
            $this->line('└─────────────────────────────────────────────┘');
            $this->newLine();

            // Si tiene relaciones, ofrecer opciones
            if ($relacionesInfo['historial_ventas'] > 0) {
                $this->warn('⚠️  Este usuario tiene registros relacionados.');
                $this->newLine();
                
                $opcion = $this->choice(
                    '¿Qué deseas hacer?',
                    [
                        '1' => 'Reasignar ventas a otro usuario',
                        '2' => 'Eliminar ventas relacionadas y luego el usuario',
                        '3' => 'Cancelar y elegir otro usuario'
                    ],
                    '3'
                );

                if ($opcion === '3' || $opcion === 'Cancelar y elegir otro usuario') {
                    $this->info('✓ Operación cancelada.');
                    $this->newLine();
                    continue;
                }

                if ($opcion === '1' || $opcion === 'Reasignar ventas a otro usuario') {
                    // Reasignar ventas
                    $this->newLine();
                    $this->info('Usuarios duplicados con el mismo teléfono:');
                    
                    $usuariosMismoTelf = User::where('telf', $usuario->telf)
                        ->where('id', '!=', $idEliminar)
                        ->get();

                    if ($usuariosMismoTelf->isEmpty()) {
                        $this->error('❌ No hay otros usuarios con el mismo teléfono para reasignar.');
                        $this->newLine();
                        continue;
                    }

                    $opcionesUsuarios = [];
                    foreach ($usuariosMismoTelf as $u) {
                        $opcionesUsuarios[$u->id] = "ID: {$u->id} - {$u->name} ({$u->email})";
                    }
                    $opcionesUsuarios['cancelar'] = 'Cancelar';

                    $idDestino = $this->choice(
                        'Selecciona el usuario al que reasignar las ventas:',
                        $opcionesUsuarios,
                        'cancelar'
                    );

                    if ($idDestino === 'cancelar') {
                        $this->info('✓ Operación cancelada.');
                        $this->newLine();
                        continue;
                    }

                    // Confirmar reasignación
                    $confirmarReasignar = $this->confirm(
                        "¿Reasignar {$relacionesInfo['historial_ventas']} ventas del usuario ID {$idEliminar} al usuario ID {$idDestino}?",
                        false
                    );

                    if (!$confirmarReasignar) {
                        $this->info('✓ Operación cancelada.');
                        $this->newLine();
                        continue;
                    }

                    try {
                        DB::beginTransaction();

                        // Reasignar ventas
                        DB::table('historial_ventas')
                            ->where('cliente_id', $idEliminar)
                            ->update(['cliente_id' => $idDestino]);

                        // Eliminar usuario
                        $usuario->delete();

                        DB::commit();

                        $this->newLine();
                        $this->info("✓ Operación exitosa:");
                        $this->line("  • {$relacionesInfo['historial_ventas']} ventas reasignadas al usuario ID {$idDestino}");
                        $this->line("  • Usuario ID {$idEliminar} eliminado");
                        $this->newLine();

                        // Remover de la colección
                        $usuariosDuplicados = $usuariosDuplicados->reject(function($u) use ($idEliminar) {
                            return $u->id === $idEliminar;
                        });

                    } catch (\Exception $e) {
                        DB::rollBack();
                        $this->error("❌ Error: " . $e->getMessage());
                        $this->newLine();
                    }

                } elseif ($opcion === '2' || $opcion === 'Eliminar ventas relacionadas y luego el usuario') {
                    // Eliminar ventas y usuario
                    $this->newLine();
                    $this->warn("⚠️  Esto eliminará:");
                    $this->line("  • {$relacionesInfo['historial_ventas']} registros de historial_ventas");
                    $this->line("  • {$relacionesInfo['productos_vendidos']} registros de historial_venta_producto");
                    $this->line("  • El usuario ID {$idEliminar}");
                    $this->newLine();

                    $confirmarEliminar = $this->confirm(
                        "🔴 ¿Estás ABSOLUTAMENTE SEGURO? Esta acción es IRREVERSIBLE",
                        false
                    );

                    if (!$confirmarEliminar) {
                        $this->info('✓ Operación cancelada.');
                        $this->newLine();
                        continue;
                    }

                    try {
                        DB::beginTransaction();

                        // Obtener IDs de historial_ventas
                        $historialVentasIds = DB::table('historial_ventas')
                            ->where('cliente_id', $idEliminar)
                            ->pluck('id');

                        // Eliminar productos de ventas
                        DB::table('historial_venta_producto')
                            ->whereIn('historial_venta_id', $historialVentasIds)
                            ->delete();

                        // Eliminar historial de ventas
                        DB::table('historial_ventas')
                            ->where('cliente_id', $idEliminar)
                            ->delete();

                        // Eliminar usuario
                        $usuario->delete();

                        DB::commit();

                        $this->newLine();
                        $this->info("✓ Eliminación exitosa:");
                        $this->line("  • Usuario ID {$idEliminar} eliminado");
                        $this->line("  • Todos los registros relacionados eliminados");
                        $this->newLine();

                        // Remover de la colección
                        $usuariosDuplicados = $usuariosDuplicados->reject(function($u) use ($idEliminar) {
                            return $u->id === $idEliminar;
                        });

                    } catch (\Exception $e) {
                        DB::rollBack();
                        $this->error("❌ Error: " . $e->getMessage());
                        $this->newLine();
                    }
                }

            } else {
                // No tiene relaciones, eliminar directamente
                $confirmar = $this->confirm(
                    "⚠️  ¿Estás SEGURO de eliminar este usuario? Esta acción NO se puede deshacer",
                    false
                );

                if (!$confirmar) {
                    $this->info('✓ Eliminación cancelada.');
                    $this->newLine();
                    continue;
                }

                try {
                    $nombreUsuario = $usuario->name;
                    $telfUsuario = $usuario->telf;
                    
                    $usuario->delete();
                    
                    $this->newLine();
                    $this->info("✓ Usuario eliminado exitosamente:");
                    $this->line("  • Nombre: {$nombreUsuario}");
                    $this->line("  • ID: {$idEliminar}");
                    $this->line("  • Teléfono: {$telfUsuario}");
                    $this->newLine();

                    // Remover de la colección
                    $usuariosDuplicados = $usuariosDuplicados->reject(function($u) use ($idEliminar) {
                        return $u->id === $idEliminar;
                    });

                } catch (\Exception $e) {
                    $this->error("❌ Error al eliminar usuario: " . $e->getMessage());
                    $this->newLine();
                }
            }

            // Verificar si aún quedan duplicados
            if ($usuariosDuplicados->isEmpty()) {
                $this->info('✓ No quedan más usuarios duplicados. Proceso completado.');
                return Command::SUCCESS;
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Verificar las relaciones del usuario
     *
     * @param int $userId
     * @return array
     */
    private function verificarRelaciones($userId)
    {
        $historialVentas = DB::table('historial_ventas')
            ->where('cliente_id', $userId)
            ->count();

        $historialVentasIds = DB::table('historial_ventas')
            ->where('cliente_id', $userId)
            ->pluck('id');

        $productosVendidos = 0;
        if ($historialVentasIds->isNotEmpty()) {
            $productosVendidos = DB::table('historial_venta_producto')
                ->whereIn('historial_venta_id', $historialVentasIds)
                ->count();
        }

        return [
            'historial_ventas' => $historialVentas,
            'productos_vendidos' => $productosVendidos,
        ];
    }

    /**
     * Obtener la cantidad de ventas del usuario
     *
     * @param int $userId
     * @return int
     */
    private function obtenerCantidadVentas($userId)
    {
        try {
            return DB::table('users as u')
                ->leftJoin('historial_ventas as hv', 'u.id', '=', 'hv.cliente_id')
                ->leftJoin('historial_venta_producto as hvp', 'hv.id', '=', 'hvp.historial_venta_id')
                ->where('u.id', $userId)
                ->count('hvp.id');
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtener el saldo del usuario
     *
     * @param int $userId
     * @return float
     */
    private function obtenerSaldo($userId)
    {
        try {
            $user = User::find($userId);
            
            if (isset($user->saldo)) {
                return (float) $user->saldo;
            }
            
            $saldo = DB::table('saldos')
                ->where('user_id', $userId)
                ->value('monto');
            
            return $saldo ? (float) $saldo : 0;
            
        } catch (\Exception $e) {
            return 0;
        }
    }
}