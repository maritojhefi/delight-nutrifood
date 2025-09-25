<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserDataComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            $view->with([
                'tiene_venta_activa' => $user->ventaActiva !== null,
            ]);
        } else {
            $view->with([
                'tiene_venta_activa' => false,
            ]);
        }
    }
}