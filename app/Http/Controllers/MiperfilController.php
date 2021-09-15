<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiperfilController extends Controller
{
    public function index(){
        return view('client.miperfil.index');
    }
}
