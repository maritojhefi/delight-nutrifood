<?php

namespace App\Observers;

use App\Models\User;
use App\Models\RegistroPunto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * NOTA: Se usa saveQuietly() al actualizar puntos para evitar bucles infinitos.
     * saveQuietly() guarda el modelo sin disparar eventos del Observer.
     */
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $verificado = $user->verificado;
        $partner_id = $user->partner_id;
        if ($verificado == 1 && $partner_id != null) {
            $partner = User::find($partner_id);
            if ($partner) {
                if ($partner->perfilesPuntos->count() > 0) {
                    $perfilPunto = $partner->perfilesPuntos->first();
                    $user->puntos += $perfilPunto->bono;
                    $user->saveQuietly(); // Evita disparar eventos del Observer
                    $registroPunto = new RegistroPunto();
                    $registroPunto->partner_id = $partner->id;
                    $registroPunto->cliente_id = $user->id;
                    $registroPunto->puntos_partner = 0;
                    $registroPunto->puntos_cliente = $perfilPunto->bono;
                    $registroPunto->total_puntos = $perfilPunto->bono;
                    $registroPunto->tipo = RegistroPunto::TIPO_BONO;
                    $registroPunto->save();
                }
            }
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($user->isDirty('verificado')) {
            $verificado = $user->verificado;
            $partner_id = $user->partner_id;
            if ($verificado == 1 && $partner_id != null) {
                $partner = User::find($partner_id);
                if ($partner) {
                    if ($partner->perfilesPuntos->count() > 0) {
                        $perfilPunto = $partner->perfilesPuntos->first();
                        $user->puntos += $perfilPunto->bono;
                        $user->saveQuietly(); // Evita disparar eventos del Observer
                        $registroPunto = new RegistroPunto();
                        $registroPunto->partner_id = $partner->id;
                        $registroPunto->cliente_id = $user->id;
                        $registroPunto->puntos_partner = 0;
                        $registroPunto->puntos_cliente = $perfilPunto->bono;
                        $registroPunto->total_puntos = $perfilPunto->bono;
                        $registroPunto->tipo = RegistroPunto::TIPO_BONO;
                        $registroPunto->save();
                    }
                }
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
