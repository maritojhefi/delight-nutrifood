<?php

namespace App\Observers;

use App\Models\Saldo;

class SaldoObserver
{
    /**
     * Handle the Saldo "created" event.
     *
     * @param  \App\Models\Saldo  $saldo
     * @return void
     */
    public function created(Saldo $saldo)
    {
        $usuario = $saldo->usuario;
        switch ($saldo->es_deuda) {
            case true:
                $usuario->increment('saldo', $saldo->monto);
                break;
            case false:
                $usuario->decrement('saldo', $saldo->monto);
                break;
            default:
                # code...
                break;
        }
        $usuario->save();
    }

    /**
     * Handle the Saldo "updated" event.
     *
     * @param  \App\Models\Saldo  $saldo
     * @return void
     */
    public function updated(Saldo $saldo)
    {
        //
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
