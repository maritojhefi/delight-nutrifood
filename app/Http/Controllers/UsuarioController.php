<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UsuarioController extends Controller
{
    public function loginWithId($id)
    {
        try {
            $idVerdadero=Crypt::decryptString($id);
            Auth::loginUsingId($idVerdadero);
            return redirect(route('miperfil'));

        } catch (\Throwable $th) {
            return redirect(route('errorLogin'));
        }
        
        
            
        
        
    }
}
