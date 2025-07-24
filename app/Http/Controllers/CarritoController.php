<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    public function index()
    {
        $user=User::find(auth()->user()->id);

        $listado = [
                (object)[
                    'id' => 1,
                    'nombre' => 'Licuado de Maracuya',
                    'descripcion' => 'Licuado frutal (incluye leche)',
                    'precio' => 17.00,
                    'imagen' => 'https://imgs.search.brave.com/Qfi-c-huarpu_eQVDaAbpgHCo6Zy1J8Kaa5wLYybgYo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMxLm1pbmhhdmlk/YS5jb20uYnIvcmVj/aXBlcy81My9lOC8y/Yy9jMC9zbW9vdGhp/ZS1kZS1tYXJhY3Vq/YS1hbXBfaGVyby0x/LmpwZw',
                    'cantidad' =>  2
                ],
                (object)[
                    'id' => 2,
                    'nombre' => 'Sandwich de Pollo a la Plancha (integral)',
                    'descripcion' => 'Sandwich de pechuga de pollo y verduras',
                    'precio' => 20.50,
                    'imagen' => 'https://imgs.search.brave.com/IV9dHgQWK5Sw3TkmrL5PaAt8Nm3qkhTjxDitXy1F2yI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90b3Rh/c3RlLmNvbS93cC1j/b250ZW50L3VwbG9h/ZHMvMjAyMi8wNi9z/YW5kd2ljaC1iYXNl/LXJlY2lwZS5qcGVn',
                    'cantidad' => 3
                ],
                (object)[
                    'id' => 3,
                    'nombre' => 'Ecobolsa Delight Clara',
                    'descripcion' => 'Bolsa reutilizable linea delight (tono claro)',
                    'precio' => 25.00,
                    'imagen' => 'https://imgs.search.brave.com/B6USg04HGTirSXeb2VylLdx0Uqyyb8r_iblnTyrRhPw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NTFZWXZvbW16S0wu/anBn',
                    'cantidad' => 1
                ],
                (object)[
                    'id' => 4,
                    'nombre' => 'PLAN MENSUAL DESAYUNO ',
                    'descripcion' => 'desayuno compuesto por una bebida fria/caliente y su acompañamiento',
                    'precio' => 320.00,
                    'imagen' => '/imagenes/delight/default-bg-1.png',
                    'cantidad' => 1 
                ],  
                (object)[
                    'id' => 5,
                    'nombre' => 'PLAN MENSUAL CENA - ALMUERZO',
                    'descripcion' => 'PLAN 20 DIAS DE CONSUMO',
                    'precio' => 359.00,
                    'imagen' => '/imagenes/delight/default-bg-1.png',
                    'cantidad' => 1
                ],
                
            ];        

        // $planes = [
        //     (object)[
        //         'id' => 9,
        //         'nombre' => 'PLAN MENSUAL DESAYUNO',
        //         'detalle' => 'PLAN 20 CONSUMOS',
        //         'editable' => false,
        //         'asignado_automatico' => false,
        //         'producto_id' => 492,
        //         'created_at' => '2023-02-15 05:42:06',
        //         'updated_at' => '2023-02-15 05:43:30',
        //         'sopa' => false,
        //         'ensalada' => false,
        //         'segundo' => false,
        //         'carbohidrato' => false,
        //         'jugo' => false,
        //     ],
        //     (object)[
        //         'id' => 8,
        //         'nombre' => 'PLAN MENSUAL CENA',
        //         'detalle' => 'PLAN DE 20 CONSUMOS Y/O CENAS',
        //         'editable' => true,
        //         'asignado_automatico' => true,
        //         'producto_id' => 478,
        //         'created_at' => '2023-01-16 05:14:16',
        //         'updated_at' => '2025-05-24 01:19:51',
        //         'sopa' => false,
        //         'ensalada' => true,
        //         'segundo' => true,
        //         'carbohidrato' => true,
        //         'jugo' => false,
        //     ],
        // ];
       

        return view('client.carrito.index',compact('user','listado'));
    }
    public function addToCarrito($id)
    {
        if(!Auth::check())
        {
            return 'logout';
        }
        $siExiste=DB::table('producto_user')->where('producto_id',$id)->where('user_id',auth()->user()->id)->first();
        if($siExiste)
        {
            DB::table('producto_user')->where('producto_id',$id)->where('user_id',auth()->user()->id)->increment('cantidad');
        }
        else
        {
            DB::table('producto_user')->insert(['user_id'=>auth()->user()->id,'producto_id'=>$id]);
        }
    }
}
