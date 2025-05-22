<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Saldo;
use Illuminate\Support\Facades\DB;

class SaldoObserver
{
    /**
     * Handle the Saldo "created" event.
     *
     * @param  \App\Models\Saldo  $saldo
     * @return void
     */


    private function actualizarSaldoUsuario($saldo)
    {
        $userId = $saldo->user_id;
        $saldos = Saldo::where('user_id', $userId)
            ->whereNull('liquidado')
            ->where('anulado', false)
            ->orderBy('created_at', 'asc')->get();

        $saldoPendiente = 0;

        foreach ($saldos as $saldo) {
            if ($saldo->es_deuda) {
                // Acumula el saldo pendiente (deuda)
                $saldoPendiente += $saldo->monto;
            } else {
                // Reduce el saldo pendiente con los pagos
                $saldoPendiente -= $saldo->monto;
            }

            // Si la deuda fue cubierta completamente, marcamos el saldo como liquidado
            if ($saldoPendiente == 0) {
                $saldo->liquidado = $saldo->created_at; // Marcamos el saldo como liquidado
                DB::table('saldos')->where('liquidado', null)
                    ->where('user_id', $userId)
                    ->where('created_at', '<', $saldo->created_at)
                    ->update(['liquidado' => $saldo->created_at]);
            }

            // Guardamos cuánto queda pendiente en cada saldo
            $saldo->saldo_restante = $saldoPendiente;
            $saldo->saveQuietly();
        }
        User::where('id', $userId)->update(['saldo', $saldoPendiente]);
    }
    public function created(Saldo $saldo)
    {
        $this->actualizarSaldoUsuario($saldo);
    }

    /**
     * Handle the Saldo "updated" event.
     *
     * @param  \App\Models\Saldo  $saldo
     * @return void
     */
    public function updating(Saldo $saldo)
    {
        if ($saldo->isDirty('anulado')) {
            $usuario = $saldo->usuario;
            switch ($saldo->anulado) {
                case true:
                    if ($saldo->es_deuda) {
                        $usuario->decrement('saldo', $saldo->monto);
                    } else {
                        $usuario->increment('saldo', $saldo->monto);
                    }
                    break;
                case false:
                    if ($saldo->es_deuda) {
                        $usuario->increment('saldo', $saldo->monto);
                    } else {
                        $usuario->decrement('saldo', $saldo->monto);
                    }
                    break;
                default:
                    # code...
                    break;
            }
            if ($saldo->liquidado) {
                Saldo::where('user_id', $saldo->user_id)->where('liquidado', $saldo->liquidado)->update(['liquidado' => null]);
            }
        }
    }
    public function updated(Saldo $saldo)
    {
        $this->actualizarSaldoUsuario($saldo);
    }
    /**
     * Handle the Saldo "deleted" event.
     *
     * @param  \App\Models\Saldo  $saldo
     * @return void
     */
    public function deleted(Saldo $saldo)
    {
        //
    }

    /**
     * Handle the Saldo "restored" event.
     *
     * @param  \App\Models\Saldo  $saldo
     * @return void
     */
    public function restored(Saldo $saldo)
    {
        //
    }

    /**
     * Handle the Saldo "force deleted" event.
     *
     * @param  \App\Models\Saldo  $saldo
     * @return void
     */
    public function forceDeleted(Saldo $saldo)
    {
        //
    }
}
