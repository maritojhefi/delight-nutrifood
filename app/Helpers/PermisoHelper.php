<?php

namespace App\Helpers;

use App\Models\Role;
use App\Models\PermisoRol;

class PermisoHelper
{
    // Permisos del rol cargados una sola vez por petición: [modulo => null (completo) | [rutas]]
    private static $cache = [];

    public static function modulos(): array
    {
        return config('permisos.modulos', []);
    }

    public static function esAdmin($usuario = null): bool
    {
        $usuario = $usuario ?? auth()->user();
        return $usuario && (int) $usuario->role_id === Role::ADMIN;
    }

    public static function permisosRol($roleId): array
    {
        if (!isset(self::$cache[$roleId])) {
            $permisos = [];
            foreach (PermisoRol::where('role_id', $roleId)->get() as $registro) {
                if ($registro->ruta === null) {
                    $permisos[$registro->modulo] = null;
                } elseif (!array_key_exists($registro->modulo, $permisos) || $permisos[$registro->modulo] !== null) {
                    $permisos[$registro->modulo][] = $registro->ruta;
                }
            }
            self::$cache[$roleId] = $permisos;
        }
        return self::$cache[$roleId];
    }

    public static function limpiarCache(): void
    {
        self::$cache = [];
    }

    /**
     * ¿El usuario puede ver el submódulo $ruta del módulo $modulo? Sin $ruta: ¿tiene algo del módulo?
     */
    public static function puede(string $modulo, ?string $ruta = null, $usuario = null): bool
    {
        $usuario = $usuario ?? auth()->user();
        if (!$usuario) {
            return false;
        }
        if (self::esAdmin($usuario)) {
            return true;
        }
        $permisos = self::permisosRol($usuario->role_id);
        if (!array_key_exists($modulo, $permisos)) {
            return false;
        }
        if ($permisos[$modulo] === null || $ruta === null) {
            return true;
        }
        return in_array($ruta, $permisos[$modulo]);
    }

    /**
     * ¿La ruta está en el catálogo? Las que no están no se restringen por permiso.
     */
    public static function rutaControlada(string $ruta): bool
    {
        foreach (self::modulos() as $modulo) {
            if (in_array($ruta, $modulo['items'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Una ruta presente en varios módulos se permite si algún módulo la concede.
     */
    public static function puedeRuta(string $ruta, $usuario = null): bool
    {
        foreach (self::modulos() as $clave => $modulo) {
            if (in_array($ruta, $modulo['items']) && self::puede($clave, $ruta, $usuario)) {
                return true;
            }
        }
        return false;
    }

    public static function primeraRutaPermitida($usuario = null): ?string
    {
        foreach (self::modulos() as $clave => $modulo) {
            foreach ($modulo['items'] as $ruta) {
                // areapanel.index necesita el parámetro del área y ventas.listar puede redirigir
                // (checkCajaOpen) generando un bucle: no sirven como destino
                if (!in_array($ruta, ['areapanel.index', 'ventas.listar']) && self::puede($clave, $ruta, $usuario)) {
                    return $ruta;
                }
            }
        }
        return null;
    }

    /**
     * URL de entrada al panel: la ruta preferida si el rol la tiene, si no su primera
     * sección permitida, y si no tiene ninguna, su perfil de la tienda.
     */
    public static function urlEntradaPanel(string $rutaPreferida, $usuario = null): string
    {
        if (self::puedeRuta($rutaPreferida, $usuario)) {
            return route($rutaPreferida);
        }
        $primera = self::primeraRutaPermitida($usuario);
        return $primera ? route($primera) : url('/miperfil');
    }

    /**
     * Filtra una lista del menú (subtitulo => ruta | ['ruta' => ..., 'params' => ...]).
     */
    public static function filtrarLista(string $modulo, array $lista): array
    {
        return array_filter($lista, function ($ruta) use ($modulo) {
            $nombreRuta = is_array($ruta) ? $ruta['ruta'] : $ruta;
            return self::puede($modulo, $nombreRuta);
        });
    }
}
