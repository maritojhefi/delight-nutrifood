<?php

namespace App\Http\Controllers;

use App\Models\Plane;
use App\Models\Producto;
use App\Models\Subcategoria;
use App\Helpers\GlobalHelper;
use Illuminate\Http\Request;
use Log;

class LineaDelightController extends Controller
{
    public function index()
    {
        try {
        $productos = Producto::select('productos.*')
            ->join('subcategorias', 'subcategorias.id', 'productos.subcategoria_id')
            ->join('categorias', 'categorias.id', 'subcategorias.categoria_id')
            ->where('productos.estado', 'activo')
            ->whereIn('categorias.nombre', ['Cocina', 'Panaderia/Reposteria'])
            ->get();        
        } catch (\Throwable $th) {
            //throw $th;
            // Log::error('Error al obtener los prodctos', [
            //     'error' => $th->getMessage(),
            //     'trace' => $th->getTraceAsString()
            // ]);
        }

        $productos = $productos->map(function ($producto) {
            $producto->tiene_stock = !($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0);
            return $producto;
        });

        $subcategorias = Subcategoria::has('productos')
            ->whereIn('categoria_id', [2,3])
            ->orderBy('subcategorias.nombre')
            ->get();

        $masVendidos = $productos->sortByDesc('cantidad_vendida')
            ->take(10);

        $masRecientes = $productos->sortByDesc('created_at')
            ->take(10);

        $enDescuento = $productos->where('descuento', '!=', null)
            ->where('descuento', '!=', 0)
            ->shuffle();

        $conMasPuntos = $productos->where('puntos', '!=', null)
            ->where('puntos', '!=', 0)
            ->shuffle()
            ->take(10);

        

        // $horarios = (object)[
        //     'manana' => GlobalHelper::processSubcategoriaFoto(Subcategoria::whereIn('id', [3, 4, 8, 10, 12, 13, 54, 56, 57])->get()),
        //     'tarde' => GlobalHelper::processSubcategoriaFoto(Subcategoria::whereIn('id', [2, 6, 9, 52, 55])->get()),
        //     'noche' => GlobalHelper::processSubcategoriaFoto(Subcategoria::whereIn('id', [8, 9, 15, 55, 58])->get())
        // ];

        $horarios = (object)[
            'manana' => GlobalHelper::processSubcategoriaFoto(
                Subcategoria::whereHas('horarios', function($query) {
                    $query->where('nombre', 'mañana');
                })->get()
            ),
            'tarde' => GlobalHelper::processSubcategoriaFoto(
                Subcategoria::whereHas('horarios', function($query) {
                    $query->where('nombre', 'tarde');
                })->get()
            ),
            'noche' => GlobalHelper::processSubcategoriaFoto(
                Subcategoria::whereHas('horarios', function($query) {
                    $query->where('nombre', 'noche');
                })->get()
            )
        ];


        return view('client.lineadelight.index', compact('subcategorias', 'masVendidos','masRecientes','enDescuento', 'conMasPuntos', 'productos', 'horarios'));
    }
    public function categoriaPlanes()
    {
        $subcategoria=Subcategoria::find(1);
        //dd($subcategoria->productos());
        return view('client.lineadelight.planes',compact('subcategoria'));
    }
    public function lineadelightHorario($horario)
    {
        switch ($horario) {
            case 'manana':
                $ids = [3, 4, 8, 10, 12, 13, 54, 56, 57]; // IDs para desayuno
                $nombreHorario = 'mañana'; // Adding a display name
                break;

            case 'tarde':
                $ids = [2, 6, 9, 52, 55]; // IDs para almuerzo
                $nombreHorario = 'tarde';
                break;

            case 'noche':
                $ids = [8, 9, 14, 55, 58]; // IDs para cena
                $nombreHorario = 'noche';
                break;

            default:
                $ids = [2, 3, 4, 6, 8, 9, 10, 12, 13, 15, 52, 53, 54, 55, 56, 57, 58]; // Ningún horario válido o todos los horarios
                $nombreHorario = 'día'; // Default display name
                break;
        }

        $arrayprueba = ["manana", "tarde", "noche"];

        $subcategorias = Subcategoria::whereIn('id', $ids)->orderBy('nombre')->get();

        $horarioData = (object)[
            'nombre' => $nombreHorario,
            'value' => $horario
        ];

        return view('client.lineadelight.subcategorias-horario', compact('subcategorias', 'horarioData'));
    }
    public function lineadelightPopulares() {
        return view('client.lineadelight.productos-populares');
    }
}
