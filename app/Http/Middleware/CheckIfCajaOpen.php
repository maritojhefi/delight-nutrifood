<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Caja;
use Illuminate\Http\Request;

class CheckIfCajaOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $caja= Caja::whereDate('created_At',Carbon::today())->get();
        foreach($caja as $cash)
        if($cash->estado=='abierto') 
        {
            return $next($request);

        }
        
        return redirect()->route('producto.listar')->with('danger','Acceso denegado, la caja diaria debe estar activa');
    }
}
