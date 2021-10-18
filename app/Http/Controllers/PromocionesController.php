<?php

namespace App\Http\Controllers;

use App\Models\Plane;
use Illuminate\Http\Request;

class PromocionesController extends Controller
{
    public function index(){
        $planes=Plane::all();
        return view('client.promociones.index',compact('planes'));
    }
}
