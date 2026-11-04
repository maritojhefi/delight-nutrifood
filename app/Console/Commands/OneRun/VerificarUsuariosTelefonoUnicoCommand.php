<?php

namespace App\Console\Commands\OneRun;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerificarUsuariosTelefonoUnicoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usuarios:verificar-telefonos-unicos
                            {--simular : Solo muestra lo que se haría, sin guardar cambios}
                            {--sin-bono : Verifica sin disparar el UserObserver (no entrega bono de referido)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marca como verificados a los usuarios no verificados cuyo número (codigo_pais + telf) es único';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $simular = $this->option('simular');
        $sinBono = $this->option('sin-bono');

        $this->info('==============================================');
        $this->info('  Verificación de usuarios con teléfono único');
        $this->info('==============================================');
        if ($simular) {
            $this->warn('  MODO SIMULACIÓN: no se guardará ningún cambio');
        }
        $this->newLine();

        // Cantidad de cuentas por número completo (codigo_pais + telf), considerando TODOS los usuarios
        $conteoPorNumero = DB::table('users')
            ->select('codigo_pais', 'telf', DB::raw('COUNT(*) as total'))
            ->whereNotNull('telf')
            ->where('telf', '!=', '')
            ->groupBy('codigo_pais', 'telf')
            ->get()
            ->mapWithKeys(function ($fila) {
                return [$fila->codigo_pais . '|' . $fila->telf => $fila->total];
            });

        $noVerificados = User::where(function ($query) {
            $query->where('verificado', false)->orWhereNull('verificado');
        })
            ->orderBy('id')
            ->get();

        $aVerificar = collect();
        $omitidos = [];

        foreach ($noVerificados as $usuario) {
            $telf = trim((string) $usuario->telf);

            if ($telf === '') {
                $omitidos[] = $this->filaOmitido($usuario, 'Sin teléfono');
                continue;
            }

            // Números de relleno como 7777777777
            if (preg_match('/^(\d)\1+$/', $telf)) {
                $omitidos[] = $this->filaOmitido($usuario, 'Número de relleno');
                continue;
            }

            $total = $conteoPorNumero[$usuario->codigo_pais . '|' . $usuario->telf] ?? 0;
            if ($total > 1) {
                $omitidos[] = $this->filaOmitido($usuario, "Número repetido en {$total} cuentas");
                continue;
            }

            $aVerificar->push($usuario);
        }

        $conPartner = $aVerificar->whereNotNull('partner_id')->count();

        $this->line("Usuarios no verificados:          {$noVerificados->count()}");
        $this->line("Se marcarán como verificados:     {$aVerificar->count()}");
        $this->line("Se quedan sin verificar:          " . count($omitidos));
        $this->line("Con referidor (posible bono):     {$conPartner}" . ($sinBono ? ' (se omitirá el bono)' : ''));
        $this->newLine();

        if (count($omitidos) > 0) {
            $this->warn('Usuarios que se quedan sin verificar:');
            $this->table(['ID', 'Nombre', 'Teléfono', 'Motivo'], $omitidos);
            $this->newLine();
        }

        if ($aVerificar->isEmpty()) {
            $this->info('✓ No hay usuarios para verificar.');
            return Command::SUCCESS;
        }

        if ($simular) {
            $this->table(
                ['ID', 'Nombre', 'Teléfono', 'Referidor'],
                $aVerificar->map(function ($usuario) {
                    return [$usuario->id, $usuario->name, $usuario->codigo_pais . ' ' . $usuario->telf, $usuario->partner_id ?? '-'];
                })->all()
            );
            $this->info('✓ Simulación terminada. Ejecuta sin --simular para aplicar los cambios.');
            return Command::SUCCESS;
        }

        if (!$this->confirm("¿Marcar {$aVerificar->count()} usuarios como verificados?", false)) {
            $this->info('✓ Operación cancelada.');
            return Command::SUCCESS;
        }

        $verificados = 0;
        $errores = 0;
        $barra = $this->output->createProgressBar($aVerificar->count());
        $barra->start();

        foreach ($aVerificar as $usuario) {
            try {
                $usuario->verificado = true;
                if ($sinBono) {
                    $usuario->saveQuietly();
                } else {
                    // Dispara UserObserver::updated → entrega el bono de referido si corresponde
                    $usuario->save();
                }
                $verificados++;
            } catch (\Throwable $e) {
                $errores++;
                Log::error('Error verificando usuario por teléfono único', [
                    'user_id' => $usuario->id,
                    'mensaje' => $e->getMessage(),
                ]);
            }
            $barra->advance();
        }

        $barra->finish();
        $this->newLine(2);
        $this->info("✓ Usuarios verificados: {$verificados}");
        if ($errores > 0) {
            $this->error("❌ Errores: {$errores} (revisa storage/logs/laravel.log)");
        }

        return Command::SUCCESS;
    }

    private function filaOmitido(User $usuario, string $motivo): array
    {
        return [$usuario->id, $usuario->name, trim($usuario->codigo_pais . ' ' . $usuario->telf), $motivo];
    }
}
