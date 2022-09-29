<?php

namespace App\Http\Controllers;

use App\Models\Tutoriale;
use Illuminate\Http\Request;

class OtrosController extends Controller
{
    public function tutorialesIndex()
    {
        $videos=Tutoriale::all();
        $videos=$videos->shuffle();
        return view('client.otros.tutoriales',compact('videos'));
    }
}
