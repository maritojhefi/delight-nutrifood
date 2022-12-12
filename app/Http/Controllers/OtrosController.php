<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tutoriale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtrosController extends Controller
{
    public function tutorialesIndex()
    {
        $videos=Tutoriale::all();
        $videos=$videos->shuffle();
        return view('client.otros.tutoriales',compact('videos'));
    }
    public function cambiarColor()
    {
        
        if(Auth::check())
        {
            $user=User::findOrFail(auth()->user()->id);
            if($user->color_page=='theme-light')
            {
                // cookie('colorPage', 'valor', 60*24*30);
                $user->color_page="theme-dark";
                $user->save();
                return "theme-dark";
            }
            else
            {
                // cookie('colorPage', 'valor', 60*24*30);
                $user->color_page="theme-light";
                $user->save();
                return "theme-light";
            }
        }
        else
        {
            return "login";
        }
    }
}
