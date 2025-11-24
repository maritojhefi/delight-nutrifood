<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telefono' => ['required', 'min:8', 'unique:users,telf',],
            // 'fecha' => ['required'],
            // 'direccion' => ['required', 'string', 'min:10', ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'telf' => $data['telefono'],
            // 'nacimiento' => $data['fecha'],
            // 'direccion' => $data['direccion'],
        ]);
    }
    public function showRegistrationForm()
    {
        $usuarioReferido = null;
        $parametros = null;
        $parametroReferido = null;
        if (request()->query()) {
            // Tiene parámetros
            $parametros = request()->query(); // array con todos los parámetros
            $ref = $parametros['ref'] ?? null;
            $dbRegistro = DB::table('perfiles_puntos_users')->where('codigo', $ref)->first();
            if ($dbRegistro) {
                $usuarioReferido = User::find($dbRegistro->user_id);
                return view('auth.register', compact('usuarioReferido'));
            } else {
                return view('auth.register');
            }
        } else {
            return view('auth.register');
        }
    }

    /**
     * Extrae el número de referencia después de los ceros
     * Ejemplos:
     * "REF000019" -> 19
     * "REF001019" -> 1019
     * "REF000600" -> 600
     * "REF123456" -> 123456
     */
    private function extraerNumeroReferencia($referencia)
    {
        // Verificar que la referencia tenga el formato correcto
        if (!preg_match('/^REF\d+$/', $referencia)) {
            return null;
        }

        // Remover el prefijo "REF"
        $numero = substr($referencia, 3);

        // Convertir a entero para eliminar ceros a la izquierda
        return (int) $numero;
    }
}
