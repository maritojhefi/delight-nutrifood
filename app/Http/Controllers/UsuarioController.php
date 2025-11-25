<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RegistroPunto;
use App\Models\NumeroWhatsapp;
use App\Helpers\WhatsappHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Imagk;

Imagk::configure(['driver' => 'imagick']);

class UsuarioController extends Controller
{
    public function loginWithId($id)
    {
        try {
            $idVerdadero = Crypt::decryptString($id);
            Auth::loginUsingId($idVerdadero);
            return redirect(route('miperfil'));
        } catch (\Throwable $th) {
            return redirect(route('errorLogin'));
        }
    }
    // public function login
    public function verificarUsuario(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json(
                [
                    'exists' => true,
                    'user' => $user,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'exists' => false,
                ],
                200,
            );
        }
    }

    private function convertHeicToJpg($file)
    {
        try {
            $img = Imagk::make($file->getRealPath());
            $tempPath = tempnam(sys_get_temp_dir(), 'conv') . '.jpg';
            $img->encode('jpg')->save($tempPath);

            return new \Illuminate\Http\UploadedFile(
                $tempPath,
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg', // Forzar extensión .jpg
                'image/jpeg',
                null,
                true,
            );
        } catch (\Exception $e) {
            // Cambiar a error para que sea visible
            throw new \Exception('No se pudo convertir la imagen HEIC. Error: ' . $e->getMessage());
        }
    }

    public function registrarUsuario(Request $request)
    {
        // dd($request->all());
        $imagenJpg = null;
        // 1. Conversión HEIC antes de validar
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $extension = strtolower($file->getClientOriginalExtension());

            if (in_array($extension, ['heic', 'heif'])) {
                $convertedFile = $this->convertHeicToJpg($file);
                $imagenJpg = $convertedFile;
                $request->files->set('foto', $convertedFile); // Reemplazar en el request
            }
        }

        // Definir las reglas de validación
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:80',
                'codigo_pais' => 'required|string|max:20',
                'telefono' => 'required|string',
                'profesion' => 'required|string|max:40',
                'dia_nacimiento' => 'required|integer|between:1,31',
                'mes_nacimiento' => 'required|integer|between:1,12',
                'ano_nacimiento' => 'required|integer|min:1900|max:' . (date('Y') - 12),
                'direccion' => 'required|string|max:100',
                'direccion_trabajo' => 'nullable|string|max:100',
                'password' => 'required|string|min:4|confirmed',
                'hijos' => 'nullable|boolean',
                'partner_id' => 'nullable|sometimes|exists:users,id',
                'foto' => [
                    'nullable', // El campo no es obligatorio
                    'mimetypes:image/jpeg,image/png,image/heic', // Formatos de archivo permitidos
                    'max:10240', // Tamaño máximo del archivo (en KB)
                ],
            ],
            [
                // Mensajes personalizados
                'name.required' => 'Por favor, ingresa tu nombre.',
                'codigo_pais.required' => 'El codigo de pais es obligatorio',
                'telefono.required' => 'El número de teléfono es obligatorio.',
                'telefono.string' => 'Por favor, ingresa un número de teléfono válido.',
                'profesion.required' => 'Por favor, ingresa tu profesión.',
                'dia_nacimiento.required' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'dia_nacimiento.between' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'mes_nacimiento.required' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'mes_nacimiento.between' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'ano_nacimiento.required' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'ano_nacimiento.min' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'ano_nacimiento.max' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'direccion.required' => 'La dirección es obligatoria.',
                'password.required' => 'Por favor, crea una contraseña.',
                'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden. Por favor, vuelve a intentarlo.',
                'foto.mimes' => 'El archivo debe ser una imagen en formato JPEG, PNG, JPG, HEIC o HEIF.',
                'foto.max' => 'El tamaño máximo permitido para la imagen es de 10 MB.',
                'partner_id.exists' => 'El partner no existe.',
            ],
        );

        // Si la validación falla, se devuelve la respuesta con los errores en formato JSON
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ],
                400,
            );
        }
        // Verificar si el usuario ya existe por su correo electrónico
        // // $user = User::where('email', $request->email)->first();

        // Verificar si el usuario ya existe por su número telefónico
        $user = User::where('codigo_pais', $request->codigo_pais)->where('telf', $request->telefono)->first();
        // Obtener el primer nombre del usuario para generar su correo
        $nameParts = explode(' ', $request->name);
        $firstName = strtolower($nameParts[0]);

        // Concatenar codigo pais y numero telefonico
        $phoneNumber = ltrim($request->codigo_pais, '+') . $request->telefono;

        if ($user) {
            // El usuario ya existe, entonces actualizamos su información
            $user->name = $request->name;
            $user->codigo_pais = $request->codigo_pais;
            $user->telf = $request->telefono;
            $user->email = $firstName . '_' . $phoneNumber . '@delight.com';
            $user->profesion = $request->profesion;
            $user->nacimiento = $request->ano_nacimiento . '-' . $request->mes_nacimiento . '-' . $request->dia_nacimiento;
            // $user->nacimiento = $request->nacimiento;
            $user->direccion = $request->direccion;
            $user->direccion_trabajo = $request->direccion_trabajo;
            $user->hijos = $request->hijos ? 1 : 0;
            // Actualizamos la contraseña si es proporcionada
            if ($request->filled('password')) {
                $user->password = $request->password;
            }
            $user->partner_id = isset($request->partner_id) && $request->partner_id != '' && $request->partner_id != null ? $request->partner_id : null;
            $user->verificado = $request->telefono_verificado;
        } else {
            // El usuario no existe, entonces creamos uno nuevo
            $user = new User();
            $user->name = $request->name;
            // $user->email = $request->email;
            $user->email = $firstName . '_' . $phoneNumber . '@delight.com';
            $user->codigo_pais = $request->codigo_pais;
            $user->telf = $request->telefono;
            $user->profesion = $request->profesion;
            $user->nacimiento = $request->ano_nacimiento . '-' . $request->mes_nacimiento . '-' . $request->dia_nacimiento;
            // $user->nacimiento = $request->nacimiento;
            $user->direccion = $request->direccion;
            $user->direccion_trabajo = $request->direccion_trabajo;
            $user->hijos = $request->hijos ? 1 : 0;
            $user->password = $request->password;
            $user->partner_id = isset($request->partner_id) && $request->partner_id != '' && $request->partner_id != null ? $request->partner_id : null;
            $user->verificado = $request->telefono_verificado;
        }
        if ($request->hasFile('foto')) {
            if ($imagenJpg != null) {
                $imagen = $imagenJpg;
            } else {
                $imagen = $request->file('foto');
            }

            try {
                // Usar el helper ProcesarImagen para procesar y guardar la imagen
                $procesarImagen = \App\Helpers\ProcesarImagen::crear($imagen)
                    ->carpeta(User::RUTA_FOTO) // Carpeta donde se guardará
                    ->dimensiones(600, null) // Redimensionar a máximo 600px de ancho
                    ->formato($imagen->getClientOriginalExtension()); // Mantener formato original

                // Guardar la imagen procesada (automáticamente usa el disco correcto según el ambiente)
                $nombreArchivo = $procesarImagen->guardar();

                // Actualizar solo el nombre del archivo en la base de datos
                $user->foto = $nombreArchivo;
            } catch (\Exception $e) {
                // Log del error pero continuar con el registro del usuario
                Log::error('Error procesando imagen de usuario: ' . $e->getMessage());
            }
        }
        $user->save();

        // Autenticar al usuario
        Auth::login($user);

        // Redirigir al usuario a la página principal
        return response()->json(
            [
                'status' => 'success',
                'redirect' => '/usuario/actualizado',
            ],
            200,
        );
    }

    public function validarRegistroPaso1(Request $request)
    {
        if ($request->telefono_verificado == 1) {
            $validacion = 'string';
        } else {
            $validacion = 'string|max:' . $request->digitos_pais . '|min:' . $request->digitos_pais;
        }

        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:80',
                'codigo_pais' => 'required|string|max:20',
                'telefono' => 'required|' . $validacion,
                'foto' => [
                    'nullable', // El campo no es obligatorio
                    'mimetypes:image/jpeg,image/png,image/heic', // Formatos de archivo permitidos
                    'max:10240', // Tamaño máximo del archivo (en KB)
                ],
            ],
            [
                // Mensajes personalizados
                'name.required' => 'Por favor, ingresa tu nombre.',
                'telefono.required' => 'El número de teléfono es obligatorio.',
                'telefono.string' => 'Por favor, ingresa un número de teléfono válido.',
                'foto.mimes' => 'El archivo debe ser una imagen en formato JPEG, PNG, JPG, HEIC o HEIF.',
                'foto.max' => 'El tamaño máximo permitido para la imagen es de 10 MB.',
            ],
        );

        // Si la validación falla, se devuelve la respuesta con los errores en formato JSON
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        $userExists = User::where('codigo_pais', $request->codigo_pais)->where('telf', $request->telefono)->exists();

        // if ($userExists) {
        //     $customErrors = [
        //         'email' => ['Este correo electrónico ya está registrado.'],
        //     ];

        //     return response()->json(
        //         [
        //             'status' => 'error',
        //             'errors' => $customErrors,
        //         ],
        //         422,
        //     );
        // }

        if ($request->telefono_verificado == 0) {
            $telefonoExists = User::where('codigo_pais', $request->codigo_pais)->where('telf', $request->telefono)->exists();

            Log::debug("Existe ya un usuario con codigo pais $request->codigo_pais y numero $request->telefono ?", [$telefonoExists]);

            // if ($telefonoExists) {
            //     $customErrors = [
            //         'telefono' => ['Este número de teléfono ya está registrado.'],
            //     ];

            //     return response()->json(
            //         [
            //             'status' => 'error',
            //             'errors' => $customErrors,
            //         ],
            //         422,
            //     );

            //     return response()->json([
            //         'status' => 'otp-update-confirmarion',
            //         'message' => 'Verificando vía whatsapp',
            //         'user_exists' => $userExists,
            //     ])
            // }

            return response()->json([
                'status' => 'otp-register-validation',
                'message' => 'Verificando vía whatsapp',
                'user_exists' => $telefonoExists,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Paso 1 validado correctamente',
            'user_exists' => $userExists,
        ]);
    }

    public function validarRegistroPaso2(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'profesion' => 'required|string|max:40',
                'dia_nacimiento' => 'required|integer|between:1,31',
                'mes_nacimiento' => 'required|integer|between:1,12',
                'ano_nacimiento' => 'required|integer|min:1900|max:' . (date('Y') - 12),
                'direccion' => 'required|string|max:100',
                'direccion_trabajo' => 'nullable|string|max:100',
            ],
            [
                'profesion.required' => 'Por favor, ingresa tu profesión.',
                'dia_nacimiento.required' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'dia_nacimiento.between' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'mes_nacimiento.required' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'mes_nacimiento.between' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'ano_nacimiento.required' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'ano_nacimiento.min' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'ano_nacimiento.max' => 'Por favor, ingresa una fecha de nacimiento válida.',
                'direccion.required' => 'La dirección es obligatoria.',
            ],
        );

        // Si la validación falla, se devuelve la respuesta con los errores en formato JSON
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        // $userExists = User::where('email', $request->email)->exists();

        return response()->json([
            'status' => 'success',
            'message' => 'Paso 2 validado correctamente',
            // 'user_exists' => $userExists
        ]);
    }

    public function validarPaso(Request $request)
    {
        // dd($request->all());
        try {
            // Determinar el paso a evaluar
            $formStep = $request->input('formStep');

            switch ($formStep) {
                case 0:
                    return $this->validarRegistroPaso1($request);
                case 1:
                    return $this->validarRegistroPaso2($request);
                    // case 3:
                    //     return $this->validateStep3($request);
                default:
                    return response()->json(
                        [
                            'status' => 'error',
                            'errors' => ['general' => ['El paso no es válido']],
                        ],
                        400,
                    );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['general' => ['Error interno del servidor']],
                ],
                500,
            );
        }
    }

    public function inicioRegistro()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect()->route('register');
    }
    public function reconocerUsuarioNFC($idEncriptado)
    {
        try {
            $idUsuario = Crypt::decrypt($idEncriptado);
            $usuario = User::find($idUsuario);
            if ($usuario) {
                return view('auth.cliente-encontrado', compact('usuario'));
            } else {
                return redirect()->route('usuario.inicio.registro');
            }
        } catch (\Throwable $th) {
            return redirect()->route('usuario.inicio.registro');
        }
    }

    public function verificarNumero(Request $request)
    {
        // dd($request->all());
        $telefono = $request->input('telefono');
        $codigoPais = $request->input('codigoPais');
        $digitosPais = $request->input('digitosPais');

        // Validar que el teléfono tenga la longitud correcta
        if (strlen($telefono) != $digitosPais) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['telefono' => ['El número de teléfono debe tener exactamente ' . $digitosPais . ' dígitos.']],
                ],
                422,
            );
        }

        // Verificar si el teléfono ya existe
        $usuarios = User::where('codigo_pais', '+' . $codigoPais)
            ->where('telf', $telefono)
            ->get();

        if ($usuarios->count() > 0) {
            foreach ($usuarios as $usuario) {
                if ($usuario->verificado == true) {
                    return response()->json(
                        [
                            'status' => 'error',
                            'errors' => ['telefono' => ['Otra persona ya tiene este número de teléfono verificado.']],
                        ],
                        400,
                    );
                }
            }
        }

        // Si llegamos aquí, el teléfono es válido para verificación
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Teléfono válido para verificación.',
            ],
            200,
        );
    }

    public function enviarCodigoVerificacion(Request $request)
    {
        $numeroWhatsapp = NumeroWhatsapp::first();
        if ($numeroWhatsapp) {
            $idRegistroNumeroWhatsapp = $numeroWhatsapp->id;
            $telefono = $request->input('telefono');
            $codigoPais = $request->input('codigoPais');
            $digitosPais = $request->input('digitosPais');
            // Validar que el teléfono tenga la longitud correcta
            if (strlen($telefono) != $digitosPais) {
                return response()->json(
                    [
                        'status' => 'error',
                        'errors' => ['telefono' => ['El número de teléfono debe tener exactamente ' . $digitosPais . ' dígitos.']],
                    ],
                    422,
                );
            }
            // Generar código de verificación
            $codigo = random_int(10000, 99999);
            // Enviar código por WhatsApp
            try {
                $plantillaWhatsapp = '';

                switch ($request->input('operacion')) {
                    case 'verificar_telefono':
                        $plantillaWhatsapp = 'delight_template_verificar_numero';
                        break;
                    case 'editar_usuario':
                        $plantillaWhatsapp = 'delight_template_verificar_numero_editar';
                        break;
                    case 'cambio_telefono_perfil':
                        Log::debug('Envío de otp para actualizacipon del teléfono en perfil cliente', [
                            'telefono' => $telefono,
                            'codigo_pais' => $codigoPais,
                            'digitosPais' => $digitosPais,
                            'codigo_a_enviarse' => $codigo,
                        ]);
                        $plantillaWhatsapp = 'delight_template_verificar_numero_editar';
                        break;
                    case 'ingreso_usuario':
                        $usuario = User::where('codigo_pais', '+' . $codigoPais)
                            ->where('telf', $telefono)
                            ->exists();
                        Log::debug("Usuario existe para ingreso con codigo pais $codigoPais y telefono $telefono ?", [$usuario]);
                        if (!$usuario) {
                            return response()->json(
                                [
                                    'status' => 'error',
                                    'errors' => ['general' => ['No existe un usuario para el número telefónico solicitado.']],
                                    'codigo_error' => 404,
                                ],
                                404,
                            );
                        }
                        $plantillaWhatsapp = 'delight_template_verificar_numero_ingreso';
                        break;
                    default:
                        $plantillaWhatsapp = 'delight_template_verificar_numero';
                        break;
                }

                WhatsappHelper::setNumero($idRegistroNumeroWhatsapp)
                    ->plantilla($plantillaWhatsapp)
                    ->para($codigoPais . $telefono)
                    ->variables(['codigo' => $codigo])
                    ->enviar();

                return response()->json(
                    [
                        'codigo_generado' => $codigo,
                        'status' => 'success',
                        'message' => 'Código de verificación enviado exitosamente.',
                    ],
                    200,
                );
            } catch (\Exception $e) {
                // Intentar extraer código de error del mensaje si existe el patrón
                $codigoError = null;
                if (preg_match('/\(Código:\s*(\d+)\)/', $e->getMessage(), $coincidencias)) {
                    $codigoError = (int) $coincidencias[1];
                } else {
                    // Si no se encuentra el patrón, usar el código de la excepción
                    $codigoError = $e->getCode() ?: 500;
                }

                Log::error('Error en enviarCodigoVerificacion', [
                    'mensaje' => $e->getMessage(),
                    'codigo' => $codigoError,
                    'telefono' => $telefono ?? null,
                    'operacion' => $request->input('operacion'),
                ]);

                // Determinar mensaje de error según el código
                if ($codigoError == 401) {
                    $mensajeUsuario = 'No se pudo autenticar con el servicio de mensajería. Intenta más tarde.';
                } else {
                    // Mensaje genérico para todos los demás errores
                    $mensajeUsuario = 'Ocurrió un error al enviar el código de verificación. Por favor, intenta nuevamente.';
                }

                return response()->json(
                    [
                        'status' => 'error',
                        'errors' => ['general' => [$mensajeUsuario]],
                        'codigo_error' => $codigoError,
                    ],
                    $codigoError == 401 ? 401 : 500,
                );
            }
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['general' => ['El sistema de verificación de teléfono no está disponible en este momento.']],
                    'codigo_error' => 500,
                ],
                500,
            );
        }
    }

    public function enviarCodigoVerificacionEditar(Request $request)
    {
        $numeroWhatsapp = NumeroWhatsapp::first();

        if (!$numeroWhatsapp) {
            Log::debug('No hay número de WhatsApp configurado para enviar códigos de verificación.');
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['general' => ['El sistema de verificación de teléfono no está disponible en este momento.']],
                    'codigo_error' => 500,
                ],
                500,
            );
        }

        $telefono = $request->input('telefono');
        $codigoPais = $request->input('codigoPais');
        $idRegistroNumeroWhatsapp = $numeroWhatsapp->id;

        // Verificar si el teléfono ya existe
        $usuario = User::where('codigo_pais', $codigoPais)->where('telf', $telefono)->first();
        if (!$usuario) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['telefono' => ['No existe un usuario para el número telefónico solicitado.']],
                ],
                400,
            );
        }

        // Generar código de verificación
        $codigo = random_int(10000, 99999);
        // Enviar código por WhatsApp
        try {
            WhatsappHelper::setNumero($idRegistroNumeroWhatsapp)
                ->plantilla('delight_template_verificar_numero_editar')
                ->para($codigoPais . $telefono)
                ->variables(['codigo' => $codigo])
                ->enviar();

            return response()->json(
                [
                    'codigo_generado' => $codigo,
                    'status' => 'success',
                    'message' => 'Código de verificación enviado exitosamente.',
                ],
                200,
            );
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getCode());
            $codigoError = null;
            // Buscar número dentro de (Código: X)
            if (preg_match('/\(Código:\s*(\d+)\)/', $e->getMessage(), $coincidencias) > 0) {
                $codigoError = $coincidencias[1];
                // dd($codigoError);
                if ($codigoError == '401') {
                    return response()->json(
                        [
                            'status' => 'error',
                            'errors' => ['general' => ['En este momento no se puede enviar el código de verificación al telefono.']],
                            'codigo_error' => $codigoError,
                        ],
                        401,
                    );
                } else {
                    return response()->json(
                        [
                            'status' => 'error',
                            'errors' => ['general' => [$e->getMessage()]],
                            'codigo_error' => $codigoError,
                        ],
                        500,
                    );
                }
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'errors' => ['general' => ['Error al enviar el código de verificación. Intenta nuevamente.']],
                        'codigo_error' => $codigoError,
                    ],
                    500,
                );
            }
        }
    }

    public function verificarCodigoOTP(Request $request)
    {
        $codigoIngresado = $request->input('codigo');
        $codigoGenerado = $request->input('codigo_generado');

        if ($codigoIngresado === $codigoGenerado) {
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Código verificado correctamente',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['codigo' => ['El código ingresado no coincide. Intenta nuevamente.']],
                ],
                422,
            );
        }
    }
    public function IniciarSesionOTP(Request $request)
    {
        $codigoIngresado = $request->input('codigo');
        $codigoGenerado = $request->input('codigo_generado');
        $telefono_completo = $request->input('telefono_completo');
        $user = User::whereRaw('CONCAT(codigo_pais, telf) = ?', [$telefono_completo])->first();

        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['telefono_completo' => ['No se encontró un usuario con ese número de teléfono.']],
                ],
                404,
            );
        }

        $user_id = $user->id;

        if ($codigoIngresado === $codigoGenerado) {
            Auth::loginUsingId($user_id);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Código verificado y sesión iniciada correctamente.',
                    'redirect_url' => route('miperfil'),
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['codigo' => ['El código ingresado no coincide. Intenta nuevamente.']],
                ],
                422,
            );
        }
    }

    public function cambiarNumeroOTP(Request $request)
    {
        $codigoIngresado = $request->input('codigo');
        $codigoGenerado = $request->input('codigoGenerado');
        $nuevoTelefonoNacional = $request->input('nuevoTelefonoNacional');
        $nuevoCodigoPais = $request->input('nuevoCodigoPais');
        $userId = $request->input('userId');

        $user = User::where('id', $userId)->first();

        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['telefono_completo' => ['No se encontró un usuario con ese número de teléfono.']],
                ],
                404,
            );
        }

        if ($codigoIngresado == $codigoGenerado) {
            // Actualizar el número telefónico del usuario
            $user->codigo_pais = '+' . $nuevoCodigoPais;
            $user->telf = $nuevoTelefonoNacional;
            $user->save();
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Código verificado y sesión iniciada correctamente.',
                    'redirect_url' => route('miperfil'),
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['codigo' => ['El código ingresado no coincide. Intenta nuevamente.']],
                ],
                422,
            );
        }
    }

    public function verificarCodigoReferido(Request $request)
    {
        $codigo = $request->input('codigo');
        $user = DB::table('perfiles_puntos_users')->where('codigo', $codigo)->first();

        if ($user) {
            // Usar select() antes de find() para obtener solo los campos necesarios
            // Incluimos 'foto' porque el accessor pathFoto lo necesita
            $usuarioReferido = User::select('id', 'name', 'foto')
                ->find($user->user_id);
            
            if ($usuarioReferido) {
                // El accessor pathFoto se calculará automáticamente al acceder a la propiedad
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Código de referido encontrado correctamente.',
                        'partner_id' => $usuarioReferido->id,
                        'user' => [
                            'id' => $usuarioReferido->id,
                            'name' => $usuarioReferido->name,
                            'pathFoto' => $usuarioReferido->pathFoto,
                        ],
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'errors' => ['codigo' => ['El usuario asociado al código no existe.']],
                    ],
                    404
                );
            }
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => ['codigo' => ['No se encontró un usuario con ese código de referido.']],
                ],
                404
            );
        }
    }
}
