<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Saldo;
use Illuminate\Support\Facades\DB;

class LiquidarSaldosCommand extends Command
{
    protected $signature = 'saldos:liquidar';
    protected $description = 'Liquida los saldos pendientes de los usuarios, marcando como liquidados los que corresponda';

    public function handle()
    {
        $this->info("⏳ Iniciando proceso de liquidación de saldos...");

        // Obtener la cantidad total de usuarios con saldos
        $totalUsuarios = User::whereHas('saldos')->count();
        
        if ($totalUsuarios === 0) {
            $this->info("🎉 No hay saldos pendientes por liquidar.");
            return;
        }

        $this->output->progressStart($totalUsuarios); // Iniciar barra de progreso

        User::whereHas('saldos') // Solo usuarios con saldos
            ->select('id')
            ->chunk(50, function ($usuarios) { // Procesa en lotes de 50 usuarios
                foreach ($usuarios as $user) {
                    DB::transaction(function () use ($user) {
                        $this->procesarSaldos($user->id);
                    });

                    $this->output->progressAdvance(); // Avanza la barra de progreso
                }
            });

        $this->output->progressFinish(); // Finaliza la barra de progreso
        $this->info("✅ Proceso de liquidación de saldos finalizado.");
    }

    private function procesarSaldos($userId)
    {
        $saldos = Saldo::where('user_id', $userId)
            ->whereNull('liquidado')
            ->where('anulado', false)
            ->orderBy('created_at', 'asc')
            ->get();

        $saldoPendiente = 0;
        $saldosActualizar = [];

        foreach ($saldos as $saldo) {
            if ($saldo->es_deuda) {
                $saldoPendiente += $saldo->monto; // Suma deuda
            } else {
                $saldoPendiente -= $saldo->monto; // Resta pago
            }

            // Si la deuda se cubrió completamente, marcar como liquidado
            if ($saldoPendiente == 0) {
                $fechaLiquidacion = $saldo->created_at;

                Saldo::where('liquidado', null)
                    ->where('user_id', $userId)
                    ->where('created_at', '<=', $fechaLiquidacion)
                    ->update(['liquidado' => $fechaLiquidacion]);
            }

            Saldo::where('id', $saldo->id)
            ->update(['saldo_restante' => $saldoPendiente]);
        }

        // Actualizar el saldo total del usuario
        User::where('id', $userId)->update(['saldo' => $saldoPendiente]);
    }
}
