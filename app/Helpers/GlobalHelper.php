<?php

namespace App\Helpers;

use Cache;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Plane;
use App\Models\Setting;
use App\Models\Almuerzo;
use App\Models\Producto;
use App\Models\Adicionale;
use Illuminate\Support\Str;
use App\Helpers\WhatsappAPIHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rawilk\Printing\Facades\Printing;
use App\Events\RefreshMenuHeaderEvent;
use App\Models\Horario;

class GlobalHelper
{
    public static function timeago($date, $parametro = null)
    {
        $timestamp = strtotime($date);

        // Traducción para unidades de tiempo
        $strTimeSingular = ['segundo', 'minuto', 'hora', 'día', 'mes', 'año'];
        $strTimePlural = ['segundos', 'minutos', 'horas', 'días', 'meses', 'años'];
        $length = [60, 60, 24, 30, 12, 10];

        $currentTime = time();

        // Validación de fecha válida
        if ($timestamp === false || $timestamp < 0) {
            return 'Fecha no válida';
        }

        $isFuture = $currentTime < $timestamp;
        $diff = abs($currentTime - $timestamp);

        // Si se especifica un parámetro de unidad, calcular específicamente en esa unidad
        if ($parametro !== null) {
            $unidades = [
                'segundo' => 0,
                'minuto' => 1,
                'hora' => 2,
                'dia' => 3,
                'día' => 3,
                'mes' => 4,
                'año' => 5,
                'ano' => 5
            ];

            $parametroLower = strtolower($parametro);

            if (isset($unidades[$parametroLower])) {
                $i = $unidades[$parametroLower];
                $diffCalculated = $diff;

                // Calcular la diferencia en la unidad específica
                for ($j = 0; $j < $i; $j++) {
                    $diffCalculated = $diffCalculated / $length[$j];
                }

                $diffCalculated = round($diffCalculated);

                // Si es día y la diferencia es 0, devolver "Hoy"
                if ($i == 3 && $diffCalculated == 0) {
                    return "Hoy";
                }

                $unit = $diffCalculated != 1 ? $strTimePlural[$i] : $strTimeSingular[$i];

                // Construir el mensaje
                if ($isFuture) {
                    return "Faltan $diffCalculated $unit";
                } else {
                    return "Hace $diffCalculated $unit";
                }
            }
        }

        // Calcular el tiempo transcurrido o faltante automáticamente
        for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
            $diff = $diff / $length[$i];
        }

        $diff = round($diff);

        // Si es día y la diferencia es 0, devolver "Hoy"
        if ($i == 3 && $diff == 0) {
            return "Hoy";
        }

        $unit = $diff > 1 ? $strTimePlural[$i] : $strTimeSingular[$i];

        // Construir el mensaje
        if ($isFuture) {
            return "Faltan $diff $unit";
        } else {
            return "Hace $diff $unit";
        }
    }

    public static function saber_dia($nombredia)
    {
        //dd(date('N', strtotime($nombredia)));
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }
    // public static function fechaFormateada(int $level, $fecha = null)
    // {
    //     switch ($level) {
    //         case 1:
    //             $formato = 'dddd D';
    //             break;
    //         case 2:
    //             $formato = 'dddd D \d\e MMMM';
    //             break;
    //         case 3:
    //             $formato = 'dddd D \d\e MMMM \d\e\l Y';
    //             break;
    //         case 4:
    //             $formato = 'D \d\e MMMM';
    //             break;
    //         case 5:
    //             $formato = 'D \d\e MMMM \d\e\l Y';
    //             break;
    //         case 6:
    //             $formato = 'hh:mm a';
    //             break;
    //         case 7:
    //             $formato = 'dddd D \d\e MMMM hh:mm a';
    //             break;
    //         default:
    //             $formato = 'dddd D \d\e MMMM \d\e\l Y';
    //             break;
    //     }
    //     if ($fecha != null) {
    //         return ucfirst(Carbon::parse($fecha)->locale('es')->isoFormat($formato));
    //     } else {
    //         return ucfirst(Carbon::now()->locale('es')->isoFormat($formato));
    //     }
    // }
    public static function armarColeccionReporteDiario($pens, $fechaSeleccionada)
    {
        $coleccion = collect();
        foreach ($pens as $lista) {
            //dd($lista);
            $detalle = $lista->detalle;
            if ($detalle != null) {
                $det = collect(json_decode($detalle, true));
                $sopaCustom = '';
                $det['SOPA'] == '' ? ($sopaCustom = '0') : ($sopaCustom = '1');

                $saberDia = WhatsappAPIHelper::saber_dia($fechaSeleccionada);
                $menu = Almuerzo::withoutGlobalScope('diasActivos')->where('dia', $saberDia)->first();
                $tipoSegundo = '';
                $tipoEnvio = '';

                $det['PLATO'] = str_replace(' ', '', $det['PLATO']);

                $menu->ejecutivo = str_replace(' ', '', $menu->ejecutivo);
                $menu->dieta = str_replace(' ', '', $menu->dieta);
                $menu->vegetariano = str_replace(' ', '', $menu->vegetariano);

                if ($det['PLATO'] == $menu->ejecutivo) {
                    $tipoSegundo = 'EJECUTIVO';
                }
                if ($det['PLATO'] == $menu->dieta) {
                    $tipoSegundo = 'DIETA';
                }
                if ($det['PLATO'] == $menu->vegetariano) {
                    $tipoSegundo = 'VEGGIE';
                }

                if ($det['ENVIO'] == Plane::ENVIO1) {
                    $tipoEnvio = 'c) ' . Plane::ENVIO1;
                }
                if ($det['ENVIO'] == Plane::ENVIO2) {
                    $tipoEnvio = 'b) ' . Plane::ENVIO2;
                }
                if ($det['ENVIO'] == Plane::ENVIO3) {
                    $tipoEnvio = 'a) ' . Plane::ENVIO3;
                }
                $coleccion->push([
                    'NOMBRE' => $lista->name,
                    'SOPA' => $sopaCustom,
                    'PLATO' => $tipoSegundo,
                    'CARBOHIDRATO' => $det['CARBOHIDRATO'],
                    'ENVIO' => $tipoEnvio,
                    'ENSALADA' => 1,
                    'EMPAQUE' => $det['EMPAQUE'],
                    'JUGO' => 1,
                    'ESTADO' => $lista->estado,
                ]);
            } else {
                $coleccion->push([
                    'NOMBRE' => $lista->name,
                    'SOPA' => '',
                    'PLATO' => '',
                    'CARBOHIDRATO' => '',
                    'ENVIO' => 'd) N/A',
                    'ENSALADA' => '',
                    'EMPAQUE' => '',
                    'JUGO' => '',
                    'ESTADO' => $lista->estado,
                ]);
            }
        }
        $coleccion = $coleccion->sortBy(['ENVIO', 'asc']);
        return $coleccion;
    }
    public static function armarColeccionReporteDiarioVista($pens, $fechaSeleccionada)
    {
        $coleccion = collect();
        foreach ($pens as $lista) {
            // dd($lista);
            $detalle = $lista->detalle;
            if ($detalle != null) {
                $det = collect(json_decode($detalle, true));
                $sopaCustom = '';
                $det['SOPA'] == '' ? ($sopaCustom = 'Sin Sopa') : ($sopaCustom = $det['SOPA']);

                $saberDia = WhatsappAPIHelper::saber_dia($fechaSeleccionada);
                $menu = Almuerzo::withoutGlobalScope('diasActivos')->where('dia', $saberDia)->first();
                $tipoSegundo = '';
                $tipoEnvio = '';

                $det['PLATO'] = str_replace(' ', '', $det['PLATO']);
                $det['SOPA'] = trim($det['SOPA']);
                $det['CARBOHIDRATO'] = trim($det['CARBOHIDRATO']);
                $menu->ejecutivo = str_replace(' ', '', $menu->ejecutivo);
                $menu->dieta = str_replace(' ', '', $menu->dieta);
                $menu->vegetariano = str_replace(' ', '', $menu->vegetariano);

                if ($det['PLATO'] == $menu->ejecutivo) {
                    $tipoSegundo = 'EJECUTIVO';
                }
                if ($det['PLATO'] == $menu->dieta) {
                    $tipoSegundo = 'DIETA';
                }
                if ($det['PLATO'] == $menu->vegetariano) {
                    $tipoSegundo = 'VEGGIE';
                }
                if ($det['ENVIO'] == Plane::ENVIO1) {
                    $tipoEnvio = 'c.- ' . Plane::ENVIO1;
                }
                if ($det['ENVIO'] == Plane::ENVIO2) {
                    $tipoEnvio = 'b.- ' . Plane::ENVIO2;
                }
                if ($det['ENVIO'] == Plane::ENVIO3) {
                    $tipoEnvio = 'a.- ' . Plane::ENVIO3;
                }
                switch ($det['ENVIO']) {
                    case Plane::ENVIO1:
                        $envioIcon = 'fa fa-table';
                        break;
                    case Plane::ENVIO2:
                        $envioIcon = 'fa fa-user';
                        break;
                    case Plane::ENVIO3:
                        $envioIcon = 'fa fa-truck';
                        break;
                    default:
                        $envioIcon = 'fa fa-user';
                        break;
                }
                $coleccion->push([
                    'ID' => $lista->id,
                    'NOMBRE' => $lista->name,
                    'ENSALADA' => $det['ENSALADA'],
                    'SOPA' => $det['SOPA'],
                    'PLATO' => $tipoSegundo,
                    'CARBOHIDRATO' => $det['CARBOHIDRATO'],
                    'JUGO' => $det['JUGO'],
                    'ENVIO' => $tipoEnvio,
                    'EMPAQUE' => $det['EMPAQUE'],
                    'ESTADO' => $lista->estado,
                    'COCINA' => $lista->cocina,
                    'PLAN' => $lista->nombre,
                    'PLAN_ID' => $lista->plane_id,
                    'USER_ID' => $lista->user_id,
                    'CLIENTE_INGRESADO' => $lista->cliente_ingresado ?? false,
                    'CLIENTE_INGRESADO_AT' => $lista->cliente_ingresado_at ?? null,
                    'ENVIO_ICON' => $envioIcon,
                    'SOPA_DESPACHADA_AT' => $lista->sopa_despachada_at ?? null,
                    'SEGUNDO_DESPACHADO_AT' => $lista->segundo_despachado_at ?? null,
                    'DESPACHADO_AT' => $lista->despachado_at ?? null,
                ]);
            } else {
                $coleccion->push([
                    'ID' => $lista->id,
                    'NOMBRE' => $lista->name,
                    'ENSALADA' => '',
                    'SOPA' => '',
                    'PLATO' => '',
                    'CARBOHIDRATO' => '',
                    'JUGO' => '',
                    'ENVIO' => 'd) N/A',
                    'EMPAQUE' => '',
                    'ESTADO' => $lista->estado,
                    'COCINA' => $lista->cocina,
                    'PLAN' => $lista->nombre,
                    'PLAN_ID' => $lista->plane_id,
                    'USER_ID' => $lista->user_id,
                    'CLIENTE_INGRESADO' => $lista->cliente_ingresado ?? false,
                    'CLIENTE_INGRESADO_AT' => $lista->cliente_ingresado_at ?? null,
                    'ENVIO_ICON' => 'fa fa-user',
                    'SOPA_DESPACHADA_AT' => null,
                    'SEGUNDO_DESPACHADO_AT' => null,
                    'DESPACHADO_AT' => null,
                ]);
            }
        }
        $coleccion = $coleccion->sortBy(['ENVIO', 'asc']);
        return $coleccion;
    }
    public static function menuDiarioArray($sopa, $plato, $ensalada, $carbohidrato, $jugo, $envio = Plane::ENVIO1, $empaque = 'Ninguno')
    {
        return [
            'SOPA' => $sopa,
            'PLATO' => $plato,
            'ENSALADA' => $ensalada,
            'CARBOHIDRATO' => $carbohidrato,
            'JUGO' => $jugo,
            'ENVIO' => $envio,
            'EMPAQUE' => $empaque,
        ];
    }
    public static function limpiarNombre($nombre)
    {
        // Eliminar espacios en blanco
        $nombre = str_replace(' ', '', $nombre);

        // Convertir a mayúsculas
        $nombre = Str::upper($nombre);

        // Reemplazar acentos y caracteres especiales
        $acentos = [
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'Ü' => 'U',
            'á' => 'A',
            'é' => 'E',
            'í' => 'I',
            'ó' => 'O',
            'ú' => 'U',
            'ü' => 'U',
            'Ñ' => 'N',
            'ñ' => 'N',
        ];
        $nombre = strtr($nombre, $acentos);

        // Eliminar caracteres que no sean letras (números, símbolos, etc.)
        $nombre = preg_replace('/[^A-Z]/', '', $nombre);

        return $nombre;
    }
    public static function generarCodigoUnico($nombreUsuario)
    {
        // Limpiar el nombre: eliminar espacios, acentos, caracteres especiales y números
        $nombreLimpio = GlobalHelper::limpiarNombre($nombreUsuario);

        // Tomar los primeros 4 caracteres como prefijo base
        // Si el nombre tiene menos de 4 caracteres, rellenar con el primer carácter repetido
        $prefijoBase = Str::substr($nombreLimpio, 0, 4);
        if (Str::length($prefijoBase) < 4 && Str::length($nombreLimpio) > 0) {
            // Rellenar con el primer carácter hasta llegar a 4
            $primerCaracter = Str::substr($prefijoBase, 0, 1);
            while (Str::length($prefijoBase) < 4) {
                $prefijoBase .= $primerCaracter;
            }
        }

        // Buscar todos los códigos existentes que empiecen con cualquier variación del prefijo
        // (desde 4 letras hasta 0 letras, ya que el prefijo se acorta cuando el número crece)
        $codigosExistentes = DB::table('perfiles_puntos_users')
            ->where(function ($query) use ($prefijoBase) {
                // Buscar códigos que empiecen con variaciones del prefijo (4, 3, 2, 1, 0 letras)
                for ($i = 4; $i >= 0; $i--) {
                    $prefijoVariacion = Str::substr($prefijoBase, 0, $i);
                    if ($i === 4) {
                        $query->where('codigo', 'like', $prefijoVariacion . '%');
                    } else {
                        $query->orWhere('codigo', 'like', $prefijoVariacion . '%');
                    }
                }
            })
            ->pluck('codigo')
            ->toArray();

        $numeroMaximo = 0;

        // Extraer el número más alto de los códigos existentes
        // Validar que el código pertenezca realmente a este prefijo
        foreach ($codigosExistentes as $codigoExistente) {
            // El código siempre tiene 5 caracteres: PREFIJO + NÚMERO
            // Validar que tenga exactamente 5 caracteres
            if (Str::length($codigoExistente) !== 5) {
                continue;
            }

            // Extraer el prefijo y el número del código existente
            // El prefijo son las letras al inicio, el número son los dígitos al final
            preg_match('/^([A-Z]*)(\d+)$/', $codigoExistente, $matches);

            if (!empty($matches[1]) && !empty($matches[2])) {
                $prefijoExistente = $matches[1];
                $numero = (int)$matches[2];

                // Validar que el prefijo existente sea una variación válida del prefijo base
                // Debe ser exactamente el prefijo base o una variación quitando del final
                $esVariacionValida = false;
                for ($i = 4; $i >= 0; $i--) {
                    $prefijoVariacion = Str::substr($prefijoBase, 0, $i);
                    if ($prefijoExistente === $prefijoVariacion) {
                        $esVariacionValida = true;
                        break;
                    }
                }

                // Solo considerar el número si el prefijo es una variación válida
                if ($esVariacionValida && $numero > $numeroMaximo) {
                    $numeroMaximo = $numero;
                }
            }
        }

        // Generar el siguiente número consecutivo
        $siguienteNumero = $numeroMaximo + 1;

        // Calcular cuántos dígitos tiene el número
        $digitosNumero = Str::length((string)$siguienteNumero);

        // Calcular cuántas letras del prefijo necesitamos
        // Total siempre es 5: letras + números = 5
        $letrasNecesarias = 5 - $digitosNumero;

        // Asegurar que no necesitemos más letras de las disponibles (máximo 4)
        if ($letrasNecesarias > 4) {
            $letrasNecesarias = 4;
        }

        // Si necesitamos más letras de las que tenemos, usar todas las disponibles
        if ($letrasNecesarias < 0) {
            $letrasNecesarias = 0;
        }

        // Tomar solo las letras necesarias del prefijo base (desde el inicio)
        // Esto es lo que hace que se "quite al revés": RODR -> ROD -> RO -> R
        $prefijoFinal = Str::substr($prefijoBase, 0, $letrasNecesarias);

        // Construir el código final: PREFIJO (letras) + NÚMERO (siempre 5 caracteres total)
        $codigo = $prefijoFinal . $siguienteNumero;

        // Asegurar que el código tenga exactamente 5 caracteres
        $codigo = Str::substr($codigo, 0, 5);

        return $codigo;
    }
    public static function actualizarCarbosDisponibilidad()
    {
        $menuHoy = Almuerzo::withoutGlobalScope('diasActivos')->hoy()->first();
        $adicionalesCocina = Adicionale::cocinaAdicionales()->get();
        // dd($menuHoy, $adicionales);
        foreach ($adicionalesCocina as $adicional) {
            switch ($adicional->codigo_cocina) {
                case 'segundo_ejecutivo':
                    $adicional->nombre = $menuHoy->ejecutivo;
                    $adicional->cantidad = $menuHoy->ejecutivo_estado ? $menuHoy->ejecutivo_cant : 0;
                    break;
                case 'segundo_dieta':
                    $adicional->nombre = $menuHoy->dieta;
                    $adicional->cantidad = $menuHoy->dieta_estado ? $menuHoy->dieta_cant : 0;
                    break;
                case 'segundo_veggie':
                    $adicional->nombre = $menuHoy->vegetariano;
                    $adicional->cantidad = $menuHoy->vegetariano_estado ? $menuHoy->vegetariano_cant : 0;
                    break;
                case 'carbohidrato_1':
                    $adicional->nombre = $menuHoy->carbohidrato_1;
                    $adicional->cantidad = $menuHoy->carbohidrato_1_estado ? $menuHoy->carbohidrato_1_cant : 0;
                    break;
                case 'carbohidrato_2':
                    $adicional->nombre = $menuHoy->carbohidrato_2;
                    $adicional->cantidad = $menuHoy->carbohidrato_2_estado ? $menuHoy->carbohidrato_2_cant : 0;
                    break;
                case 'carbohidrato_3':
                    $adicional->nombre = $menuHoy->carbohidrato_3;
                    $adicional->cantidad = $menuHoy->carbohidrato_3_estado ? $menuHoy->carbohidrato_3_cant : 0;
                    break;
                case 'sopa':
                    $adicional->nombre = $menuHoy->sopa;
                    $adicional->cantidad = $menuHoy->sopa_estado ? $menuHoy->sopa_cant : 0;
                    break;
                default:
                    # code...
                    break;
            }
            $adicional->save();
        }
        event(new RefreshMenuHeaderEvent());
    }

    public static function actualizarMenuCantidadDesdePOS(Adicionale $adicional, $accion = 'reducir', $cantidad = 1)
    {
        // Obtén el menú de hoy
        $menuHoy = Almuerzo::withoutGlobalScope('diasActivos')->hoy()->first();

        // Verifica si se encontró un menú para hoy
        if (!$menuHoy) {
            return;
        }

        // Determina el método a usar (increment o decrement) en función de la acción
        $method = $accion === 'aumentar' ? 'increment' : 'decrement';

        // Actualiza el campo correspondiente basado en el código de cocina
        switch ($adicional->codigo_cocina) {
            case 'segundo_ejecutivo':
                $menuHoy->{$method}('ejecutivo_cant', $cantidad);
                break;
            case 'segundo_dieta':
                $menuHoy->{$method}('dieta_cant', $cantidad);
                break;
            case 'segundo_veggie':
                $menuHoy->{$method}('vegetariano_cant', $cantidad);
                break;
            case 'carbohidrato_1':
                $menuHoy->{$method}('carbohidrato_1_cant', $cantidad);
                break;
            case 'carbohidrato_2':
                $menuHoy->{$method}('carbohidrato_2_cant', $cantidad);
                break;
            case 'carbohidrato_3':
                $menuHoy->{$method}('carbohidrato_3_cant', $cantidad);
                break;
            case 'sopa':
                $menuHoy->{$method}('sopa_cant', $cantidad);
                break;
            default:
                // Código desconocido, no hacer nada
                return;
        }

        // Guarda los cambios en el menú de hoy
        $menuHoy->save();
        event(new RefreshMenuHeaderEvent());
    }
    public static function cantidadColor($cantidad)
    {
        switch (true) {
            case $cantidad <= 5:
                return 'danger';
            case $cantidad > 5 && $cantidad <= 10:
                return 'warning';
            case $cantidad > 10:
                return 'primary';
            default:
                return 'primary'; // Opcional: para manejar cualquier caso inesperado
        }
    }
    public static function fechaFormateada(int $level, $fecha = null)
    {
        switch ($level) {
            case 1:
                $formato = 'dddd D';
                break;
            case 2:
                $formato = 'dddd D \d\e MMMM';
                break;
            case 3:
                $formato = 'dddd D \d\e MMMM \d\e\l Y';
                break;
            case 4:
                $formato = 'D \d\e MMMM';
                break;
            case 5:
                $formato = '';
                break;
            case 6:
                $formato = 'hh:mm a';
                break;
            case 7:
                $formato = 'dddd D \d\e MMMM hh:mm a';
                break;
            case 8:
                $formato = 'hh:mm';
                break;
            case 9:
                $formato = 'hh:mm a';
                break;
            case 10:
                $formato = 'D \d\e MMMM \d\e\l Y \a \l\a\s hh:mm a';
                break;
            case 11:
                $formato = 'dddd';
                break;

            default:
                $formato = 'dddd D \d\e MMMM \d\e\l Y';
                break;
        }
        if ($fecha != null) {
            return ucfirst(Carbon::parse($fecha)->locale('es')->isoFormat($formato));
        } else {
            return ucfirst(Carbon::now()->locale('es')->isoFormat($formato));
        }
    }
    public static function limpiarTextoParaPOS($texto)
    {
        // Normaliza a forma con caracteres separados (NFD)
        $texto = \Normalizer::normalize($texto, \Normalizer::FORM_D);

        // Elimina los caracteres de acento (diacríticos)
        $texto = preg_replace('/[\p{Mn}]/u', '', $texto);

        // Elimina cualquier carácter no ASCII imprimible
        $texto = preg_replace('/[^\x20-\x7E]/', '', $texto);

        return $texto;
    }

    public static function obtenerModeloProducto($productoId)
    {
        $producto = Producto::findOrFail($productoId);
        if ($producto) {
            return $producto;
        } else {
            return null;
        }
    }
    public static function getValorAtributoSetting($atributo)
    {
        $atributo = Setting::where('atributo', $atributo)->first();
        if (!$atributo) {
            return null;
        }
        return $atributo->valor;
    }

    public static function processSubcategoriaFoto($subcategorias)
    {
        return $subcategorias->map(function ($sub) {
            $sub->foto = $sub->foto ? $sub->pathFoto : asset(GlobalHelper::getValorAtributoSetting('bg_default'));
            return $sub;
        });
    }

    public static function formatearNumeroDecimalesMiles($numero)
    {
        $numeroFloat = (float) $numero;

        // Si el número es mayor a 1000: sin decimales (pesos chilenos)
        if ($numeroFloat > 1000) {
            return number_format($numeroFloat, 0, ',', '.');
        } else {
            // Si el número es menor o igual a 1000: con decimales necesarios (UF)
            // Formatear con 2 decimales y luego eliminar ceros innecesarios
            $formateado = number_format($numeroFloat, 2, ',', '.');

            // Eliminar ceros al final y coma si no hay decimales
            $formateado = rtrim($formateado, '0');
            $formateado = rtrim($formateado, ',');

            return $formateado;
        }
    }
    public static function cachearProductos()
    {
        Cache::forget('productos'); // Retirar el viejo valor de cache

        // Cacheado de los productos disponibles, incluyendo unicamente la informacion mas relevante
        Cache::remember('productos', 60, function () {
            return Producto::publicoTienda()
                ->select(['id', 'nombre', 'precio', 'descuento', 'subcategoria_id', 'imagen'])
                ->with([
                    // Inclusion de registros relacionados necesarios para el manejo comun de los productos
                    'subcategoria:id,nombre,categoria_id',
                    'subcategoria.categoria:id,nombre',
                    'tag:id,icono',
                ])
                ->get();
        });
    }

    public static function planActualSegunHora($usuario, $fecha, $hora)
    {
        $usuario = User::find($usuario);
        $planes = $usuario->planesHoy($fecha);
        $plan = $planes
            ->whereHas('horario', function ($query) use ($hora) {
                $query->where('hora_inicio', '<=', $hora)->where('hora_fin', '>=', $hora);
            })
            ->get();
        if ($plan->isEmpty()) {
            return null;
        }
        return $plan[0];
    }

    public static function planInteligenteSegunHora($usuario, $fecha, $hora)
    {
        $usuario = User::find($usuario);
        $horaActual = Carbon::parse($hora);
        $fechaActual = Carbon::parse($fecha);

        // Obtener planes del día actual con horarios cargados
        $planesHoy = $usuario->planesHoy($fecha)->get()->load('horario');

        // Filtrar planes que no tienen horario Y que no están en permiso
        $planesHoy = $planesHoy->filter(fn($plan) =>
            $plan->horario !== null &&
            $plan->pivot->estado !== "permiso"  // Cambio aquí: pivot->estado
        );

        // Si no hay planes hoy, buscar el próximo plan pendiente
        if ($planesHoy->isEmpty()) {
            // Obtener todos los planes pendientes del usuario
            $planesPendientes = $usuario->planesPendientes()->get()->load('horario');

            // Filtrar planes que tienen horario, no están en permiso Y son de fecha futura (mayor a hoy)
            $planesPendientes = $planesPendientes->filter(fn($plan) =>
                $plan->horario !== null &&
                $plan->pivot->estado !== "permiso" &&
                Carbon::parse($plan->pivot->start)->greaterThan($fechaActual)
            );

            if ($planesPendientes->isEmpty()) {
                return null;
            }

            // Ordenar por fecha de inicio (start) y hora de inicio del horario combinados
            $planesPendientesOrdenados = $planesPendientes->sortBy(function ($plan) {
                return Carbon::parse($plan->pivot->start)
                    ->setTimeFromTimeString($plan->horario->hora_inicio)
                    ->timestamp;
            });

            $planSiguiente = $planesPendientesOrdenados->first();
            $fechaPlanSiguiente = $planSiguiente->pivot->start;

            $pedidos = self::obtenerPedidosDelPlan($usuario->id, $planSiguiente, $fechaPlanSiguiente);

            // Calcular tiempo hasta el inicio del plan
            $fechaHoraInicioSiguiente = Carbon::parse($fechaPlanSiguiente)->setTimeFromTimeString($planSiguiente->horario->hora_inicio);
            $tiempoRestante = $horaActual->diffInMinutes($fechaHoraInicioSiguiente);

            return (object) [
                'plan' => $planSiguiente,
                'pedidos' => $pedidos,
                'estado' => 'proximo_dia',
                'tiempo_restante' => $tiempoRestante,
                'planes_restantes' => $planesPendientes->count(),
                'fecha_plan' => $fechaPlanSiguiente,
            ];
        }

        // Resto del código sin cambios...
        // Ordenar planes del día actual por hora de inicio
        $planesConHorarios = $planesHoy->sortBy(function ($plan) {
            return Carbon::parse($plan->horario->hora_inicio);
        });

        $planActual = null;
        $planProximo = null;
        $estado = '';
        $tiempoRestante = 0;
        $planesRestantesHoy = 0;

        foreach ($planesConHorarios as $plan) {
            $horaInicio = Carbon::parse($plan->horario->hora_inicio);
            $horaFin = Carbon::parse($plan->horario->hora_fin);

            if ($horaActual->between($horaInicio, $horaFin)) {
                $planActual = $plan;
                $estado = 'en_curso';
                $tiempoRestante = $horaActual->diffInMinutes($horaFin);
                break;
            }

            if ($horaActual->lt($horaInicio)) {
                if (!$planProximo) {
                    $planProximo = $plan;
                    $estado = 'proximo';
                    $tiempoRestante = $horaActual->diffInMinutes($horaInicio);
                }
                $planesRestantesHoy++;
            }
        }

        // Si no hay plan actual ni próximo en el día actual, buscar próximo plan pendiente
        if (!$planActual && !$planProximo) {
            // Obtener todos los planes pendientes del usuario
            $planesPendientes = $usuario->planesPendientes()->get()->load('horario');

            // Filtrar planes que tienen horario, no están en permiso y son después de hoy
            $planesPendientes = $planesPendientes->filter(fn($plan) =>
                $plan->horario !== null &&
                $plan->pivot->estado !== "permiso" &&
                Carbon::parse($plan->pivot->start)->greaterThan($fechaActual)
            );

            if ($planesPendientes->isNotEmpty()) {
                // Ordenar por fecha de inicio (start) y luego por hora de inicio del horario
                $planesPendientesOrdenados = $planesPendientes->sortBy(function ($plan) {
                    return Carbon::parse($plan->pivot->start)
                        ->setTimeFromTimeString($plan->horario->hora_inicio)
                        ->timestamp;
                });


                $planSiguiente = $planesPendientesOrdenados->first();
                $fechaPlanSiguiente = $planSiguiente->pivot->start;

                $pedidos = self::obtenerPedidosDelPlan($usuario->id, $planSiguiente, $fechaPlanSiguiente);

                // Calcular tiempo hasta el inicio del plan
                $fechaHoraInicioSiguiente = Carbon::parse($fechaPlanSiguiente)->setTimeFromTimeString($planSiguiente->horario->hora_inicio);
                $tiempoRestante = $horaActual->diffInMinutes($fechaHoraInicioSiguiente);

                return (object) [
                    'plan' => $planSiguiente,
                    'pedidos' => $pedidos,
                    'estado' => 'proximo_dia',
                    'tiempo_restante' => $tiempoRestante,
                    'planes_restantes' => $planesPendientes->count(),
                    'fecha_plan' => $fechaPlanSiguiente,
                ];
            }

            return null;
        }

        $planFinal = $planActual ?: $planProximo;

        $pedidos = self::obtenerPedidosDelPlan($usuario->id, $planFinal, $fecha);

        return (object) [
            'plan' => $planFinal,
            'pedidos' => $pedidos,
            'estado' => $estado,
            'tiempo_restante' => $tiempoRestante,
            'planes_restantes' => $planesRestantesHoy,
            'fecha_plan' => $fecha,
        ];
    }

    protected static function obtenerPedidosDelPlan($userId, $plan, $fecha)
    {
        $start_timestamp_plan_seleccionado = $plan->pivot->start;

        $pedidos = DB::table('plane_user')
            // Filtrar por el usuario actual
            ->where('user_id', $userId)
            // Filtrar por el ID del plan (tipo de plan, ej. Almuerzo)
            ->where('plane_id', $plan->id)
            // Filtrar por la fecha completa para asegurar la coincidencia del día
            ->whereDate('start', $fecha)
            // Filtro clave: Agrupar por la hora de inicio exacta del plan seleccionado
            ->where('start', $start_timestamp_plan_seleccionado)
            // Filtrar para evitar pedidos con permiso
            ->whereNotIn('estado', ['permiso'])
            ->get(); // Obtener la colección de todos los registros coincidentes

        return $pedidos;
    }


    public static function discoArchivos()
    {
        return config('filesystems.default');
    }

    public static function horarioHoraActual()
    {
        $horaActual = Carbon::now()->format('H:i:s');

        $horario = Horario::whereTime('hora_inicio', '<=', $horaActual)
            ->whereTime('hora_fin', '>=', $horaActual)
            ->orderBy('posicion')
            ->first();

        return $horario;
    }
}
