<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Venta; // Assuming Venta model is in App\Models

class UserDataComposer
{
    public function compose(View $view)
    {
        // Valores por defecto para usuarios sin autenticar
        $tieneVentaActiva = false;
        $cantidadTotalPedido = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $ventaActiva = $user->ventaActiva; 
            
            if ($ventaActiva) {
                $tieneVentaActiva = true;
                $cantidadTotalPedido = $ventaActiva->productos()->sum('cantidad');
            }
        }
        
        // Pasar las variables a todas las vistas enlazadas al composer
        $view->with([
            'tiene_venta_activa' => $tieneVentaActiva,
            'cantidad_total_pedido' => $cantidadTotalPedido,
        ]);
    }
}
