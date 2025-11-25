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
        $this->procesarBonoReferido($user);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        // Solo procesar si el campo 'verificado' cambió
        if ($user->isDirty('verificado')) {
            $this->procesarBonoReferido($user);
        }
    }

    /**
     * Procesa el bono de referido cuando un usuario es verificado y tiene un partner.
     * 
     * @param  \App\Models\User  $user
     * @return void
     */
    private function procesarBonoReferido(User $user)
    {
        $verificado = $user->verificado;
        $partner_id = $user->partner_id;
        
        if ($verificado == 1 && $partner_id != null) {
            $partner = User::find($partner_id);
            
            if ($partner && $partner->perfilesPuntos->count() > 0) {
                $perfilPunto = $partner->perfilesPuntos->first();
                
                // Agregar puntos al usuario
                $user->puntos += $perfilPunto->bono;
                $user->saveQuietly(); // Evita disparar eventos del Observer
                
                // Registrar el bono en la tabla de registros
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
