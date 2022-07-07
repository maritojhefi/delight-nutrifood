<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class RevisarDatosPerfil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $usuario=User::find(auth()->user()->id);
        if($usuario->latitud==null || $usuario->telf==null || $usuario->direccion==null)
        {
            return redirect(route('llenarDatosPerfil'));
        }
        else
        {
            return $next($request);
        }
        
    }
}
