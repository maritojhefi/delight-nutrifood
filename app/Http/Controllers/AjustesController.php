<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjustesController extends Controller
{
    public function index(){
        session(['theme' => 'theme-light']);
        return view('client.ajustes.index');
    }
}
