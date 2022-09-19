<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasCocinaController extends Controller
{
    public function index()
    {
        return view('client.ventas.pedido');
    }
}
