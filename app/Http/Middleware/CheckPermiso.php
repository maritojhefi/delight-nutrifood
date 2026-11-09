<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\PermisoHelper;

class CheckPermiso
{
    /**
     * Bloquea las rutas del catálogo (config/permisos.php) si el rol no tiene el permiso.
     */
    public function handle(Request $request, Closure $next)
    {
        $ruta = optional($request->route())->getName();

        if (!$ruta || !PermisoHelper::rutaControlada($ruta) || PermisoHelper::puedeRuta($ruta)) {
            return $next($request);
        }

        $destino = PermisoHelper::primeraRutaPermitida();
        if ($destino) {
            return redirect()->route($destino)->with('danger', 'Acceso denegado, tu rol no tiene permiso para ver esta sección');
        }
        return redirect('/miperfil');
    }
}
