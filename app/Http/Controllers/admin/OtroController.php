<?php

namespace App\Http\Controllers\admin;

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
    public function index(){
        return view('admin.otros.index');
    }
}
