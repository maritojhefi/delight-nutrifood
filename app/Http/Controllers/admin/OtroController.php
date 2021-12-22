<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Asistencia;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Imports\ProductosImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class OtroController extends Controller
{
    public function importar(Request $request)
    {
        
            $import = new ProductosImport();
            Excel::import($import, request()->file('registros'));
            return redirect(route('importar.index'))->with('success','Archivo importado con exito!');
      
       
       
    }
    public function importarUser(Request $request)
    {
        
            $import = new UsersImport();
            Excel::import($import, request()->file('registros'));
            return redirect(route('importar.index'))->with('success','Usuarios importados con exito!');
      
       
       
    }
    public function marcar()
    {
        $ahora=Carbon::now()->toDateString();
       $usuario=auth()->user()->id;
        $asistencia=Asistencia::where('user_id',$usuario)->whereDate('entrada',$ahora)->first();
        
        if($asistencia==null)
        {
            Asistencia::create([
                'user_id'=>$usuario,
                'entrada'=>Carbon::now(),
            ]);
            return redirect(route('marcado'))->with('success', 'Marcaste tu entrada exitosamente!');
        }
        else
        {
            $asistencia->salida=Carbon::now();
            $asistencia->save();
            return redirect(route('marcado'))->with('success', 'Marcaste tu salida exitosamente!');
        }
    }

    public function marcado()
    {
        return view('admin.otros.marcado');
    }
    public function index(){
        return view('admin.otros.index');
    }
}
