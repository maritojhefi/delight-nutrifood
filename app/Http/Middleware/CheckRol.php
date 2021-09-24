<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;

class CheckRol
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
        if(auth()->user()->role->id==Role::ADMIN) 
        {
            return $next($request);

        }
        if(auth()->user()->role->id==Role::CLIENTE) 
        {
            return redirect('/');

        }
    }
}
