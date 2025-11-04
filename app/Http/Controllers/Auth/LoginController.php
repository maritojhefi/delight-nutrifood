<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        $codigo_pais = $request->input('codigo_pais');
        
        return [
            'codigo_pais' => '+' . $codigo_pais,
            'telf' => $request->input('telf'),
            'password' => $request->input('password'),
        ];
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'codigo_pais' => 'required|string',
            'telf' => 'required|string',
            'password' => 'required|string',
        ], [
            'telf.required' => 'El número de teléfono es requerido.',
            'password.required' => 'La contraseña es requerida.',
            'codigo_pais.required' => 'El código de país es requerido.',
        ]);
    }
}