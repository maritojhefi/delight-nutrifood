<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
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
                true
            );
        } catch (\Exception $e) {
            // Cambiar a error para que sea visible
            throw new \Exception("No se pudo convertir la imagen HEIC. Error: " . $e->getMessage());
        }
    }

    public function registrarUsuario(Request $request)
    {
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
                'email' => 'required|email',
                'codigo_pais'=> 'required|string|max:20',
                'telefono' => 'required|string|max:20|min:8',
                'profesion' => 'required|string|max:40',
                'dia_nacimiento' => 'required|integer|between:1,31',
                'mes_nacimiento' => 'required|integer|between:1,12',
                'ano_nacimiento' => 'required|integer|min:1900|max:' . (date('Y') - 12),
                'direccion' => 'required|string|max:100',
                'direccion_trabajo' => 'nullable|string|max:100',
                'password' => 'required|string|min:4|confirmed',
                'hijos' => 'nullable|boolean',
                'foto' => [
                    'nullable', // El campo no es obligatorio
                    'mimetypes:image/jpeg,image/png,image/heic', // Formatos de archivo permitidos
                    'max:10240', // Tamaño máximo del archivo (en KB)
                ],
            ],
            [
                // Mensajes personalizados
                'name.required' => 'Por favor, ingresa tu nombre.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'Por favor, ingresa un correo electrónico válido.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
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
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // El usuario ya existe, entonces actualizamos su información
            $user->name = $request->name;
            $user->codigo_pais = $request->codigo_pais;
            $user->telf = $request->telefono;
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
        } else {
            // El usuario no existe, entonces creamos uno nuevo
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->codigo_pais = $request->codigo_pais;
            $user->telf = $request->telefono;
            $user->profesion = $request->profesion;
            $user->nacimiento = $request->ano_nacimiento . '-' . $request->mes_nacimiento . '-' . $request->dia_nacimiento;
            // $user->nacimiento = $request->nacimiento;
            $user->direccion = $request->direccion;
            $user->direccion_trabajo = $request->direccion_trabajo;
            $user->hijos = $request->hijos ? 1 : 0;
            $user->password = $request->password;

            // Guardamos el nuevo usuario
        }
        if ($request->hasFile('foto')) {
            if ($imagenJpg != null) {
                $imagen = $imagenJpg;
            } else {
                $imagen = $request->file('foto');
            }
            // Generar un nombre único para la imagen
            $nombreArchivo = uniqid() . '.' . $imagen->getClientOriginalExtension();

            // Ruta de almacenamiento
            $ruta = public_path('imagenes/perfil/' . $nombreArchivo);

            // Redimensionar y guardar la imagen
            $image = Imagk::make($imagen)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->orientate();
            $image->save($ruta, 80); // Comprimir al 80% de calidad
            $user->foto = $nombreArchivo;
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

    public function validarRegistroPaso1(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:80',
                'email' => 'required|email',
                'codigo_pais'=> 'required|string|max:20',
                'telefono' => 'required|string|max:20|min:8',
                'foto' => [
                    'nullable', // El campo no es obligatorio
                    'mimetypes:image/jpeg,image/png,image/heic', // Formatos de archivo permitidos
                    'max:10240', // Tamaño máximo del archivo (en KB)
                ],
            ],
            [
                // Mensajes personalizados
                'name.required' => 'Por favor, ingresa tu nombre.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'Por favor, ingresa un correo electrónico válido.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
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

        $userExists = User::where('email', $request->email)->exists();

        if ($userExists) {
            $customErrors = [
                'email' => ['Este correo electrónico ya está registrado.']
            ];
            
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => $customErrors,
                ],
                422,
            );
        }

        $telefonoExists = User::where('codigo_pais', $request->codigo_pais)
                                ->where('telf', $request->telefono)
                                ->exists();

        if ($telefonoExists) {
            $customErrors = [
                'telefono' => ['Este número de teléfono ya está registrado.']
            ];
            
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => $customErrors,
                ],
                422,
            );
        }

        return response()->json([
        'status' => 'success',
        'message' => 'Paso 1 validado correctamente',
        'user_exists' => $userExists
    ]);
    }

     public function validarRegistroPaso2(Request $request){
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
                    return response()->json([
                        'status' => 'error',
                        'errors' => ['general' => ['El paso no es válido']]
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => ['general' => ['Error interno del servidor']]
            ], 500);
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
}
