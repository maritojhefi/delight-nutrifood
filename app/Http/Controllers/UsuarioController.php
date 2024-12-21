<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

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
            return response()->json([
                'exists' => true,
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'exists' => false
            ], 200);
        }
    }
    public function registrarUsuario(Request $request)
    {
        // Definir las reglas de validación
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'required|digits_between:7,15',
            'profesion' => 'required|string|max:255',
            'dia_nacimiento' => 'required|integer|between:1,31',
            'mes_nacimiento' => 'required|integer|between:1,12',
            'ano_nacimiento' => 'required|integer|min:1900|max:' . date('Y'),
            'direccion' => 'required|string|max:255',
            'direccion_trabajo' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'hijos' => 'nullable|boolean',
            'foto' => [
                'required',             // El campo es obligatorio
                'image',                // Verifica que el archivo sea una imagen
                'mimes:jpeg,png,jpg',   // Formatos de archivo permitidos
                'max:4096',             // Tamaño máximo del archivo (en KB)
            ],
        ], [
            // Mensajes personalizados
            'name.required' => 'Nombre es requerido',
            'email.required' => 'Correo es requerido',
            'email.email' => 'Correo inválido',
            'email.unique' => 'Correo ya existe',
            'telefono.required' => 'Teléfono es requerido',
            'telefono.digits_between' => 'Teléfono inválido',
            'profesion.required' => 'Profesión es requerida',
            'dia_nacimiento.required' => 'Día es requerido',
            'dia_nacimiento.between' => 'Día no válido',
            'mes_nacimiento.required' => 'Mes es requerido',
            'mes_nacimiento.between' => 'Mes no válido',
            'ano_nacimiento.required' => 'Año es requerido',
            'ano_nacimiento.min' => 'Año inválido',
            'ano_nacimiento.max' => 'Año inválido',
            'direccion.required' => 'Dirección es requerida',
            'password.required' => 'Contraseña es requerida',
            'password.min' => 'Mínimo 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'foto.required' => 'La foto es obligatoria.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'Solo se permiten archivos con formato jpeg, png o jpg.',
            'foto.max' => 'El tamaño máximo permitido es de 2MB.',
        ]);

        // Si la validación falla, se devuelve la respuesta con los errores en formato JSON
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }
        
        // Verificar si el usuario ya existe por su correo electrónico
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // El usuario ya existe, entonces actualizamos su información
            $user->name = $request->name;
            $user->telf = $request->telefono;
            $user->profesion = $request->profesion;
            $user->nacimiento = $request->ano_nacimiento . '-' . $request->mes_nacimiento . '-' . $request->dia_nacimiento;
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
            $user->telf = $request->telefono;
            $user->profesion = $request->profesion;
            $user->nacimiento = $request->ano_nacimiento . '-' . $request->mes_nacimiento . '-' . $request->dia_nacimiento;
            $user->direccion = $request->direccion;
            $user->direccion_trabajo = $request->direccion_trabajo;
            $user->hijos = $request->hijos ? 1 : 0;
            $user->password = $request->password;

            // Guardamos el nuevo usuario
            
        }
        if ($request->hasFile('foto')) {
            $imagen = $request->file('foto');

            // Generar un nombre único para la imagen
            $nombreArchivo = uniqid() . '.' . $imagen->getClientOriginalExtension();

            // Ruta de almacenamiento
            $ruta = public_path('imagenes/perfil/' . $nombreArchivo);

            // Redimensionar y guardar la imagen
            $image = Image::make($imagen)
                ->resize(600, null, function ($constraint) {
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
        return response()->json([
            'status' => 'success',
            'redirect' => '/usuario/actualizado'
        ], 200);
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
