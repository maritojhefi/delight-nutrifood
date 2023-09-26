<?php

namespace App\Http\Controllers\admin;

use App\Helpers\GlobalHelper;
use Carbon\Carbon;
use App\Models\Contrato;
use App\Models\Asistencia;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Imports\ProductosImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class OtroController extends Controller
{
    public function marcarAsistencia(Request $request)
    {
        $siEsEmpleado = Contrato::where('user_id', auth()->user()->id)->first();
        if ($siEsEmpleado) {
            $siExiste = DB::table('contrato_user')->where('user_id', $siEsEmpleado->user_id)->whereDate('created_at', Carbon::today())->where('salida', null)->first();
            $diaActual = GlobalHelper::saber_dia(Carbon::today());
            try {
                $diaMinuscula=strtolower($diaActual);
                
                $horaEntrada = Carbon::parse(json_decode($siEsEmpleado->hora_entrada)->$diaMinuscula);
                $horaSalida = Carbon::parse(json_decode($siEsEmpleado->hora_salida)->$diaMinuscula);
                // dd($horaEntrada, $horaSalida);
                $horaActual = Carbon::now();
                if ($siExiste) {
                    $diferencia = $horaSalida->diffInMinutes($horaActual);
                    if ($horaActual->gt($horaSalida)) {
                    } else {
                        $diferencia = '-' . $diferencia;
                    }
                    $tiempoTotal = Carbon::parse($siExiste->entrada)->diffInMinutes($horaActual);
                    DB::table('contrato_user')->where('id', $siExiste->id)->update(['tiempo_total' => $tiempoTotal, 'salida' => Carbon::now(), 'diferencia_salida' => $diferencia]);
                    return redirect(route('marcacion.salida', $diferencia));
                } else {
                    $diferencia = $horaEntrada->diffInMinutes($horaActual);
                    if ($horaActual->gt($horaEntrada)) {
                        $diferencia = '-' . $diferencia;
                    } else {
                    }

                    DB::table('contrato_user')->insert(['entrada' => Carbon::now(), 'diferencia_entrada' => $diferencia, 'contrato_id' => $siEsEmpleado->id, 'user_id' => $siEsEmpleado->user_id, 'created_at' => Carbon::today(), 'updated_at' => Carbon::today()]);
                    return redirect(route('marcacion.entrada', $diferencia));
                }
                
            } catch (\Throwable $th) {
                dd($th);
                return redirect()->route('noEsEmpleado')->with('error','Algo salio mal');
            }
        } else {
            return redirect(route('noEsEmpleado'));
        }
    }
    public function importar(Request $request)
    {

        $import = new ProductosImport();
        Excel::import($import, request()->file('registros'));
        return redirect(route('importar.index'))->with('success', 'Archivo importado con exito!');
    }
    public function importarUser(Request $request)
    {

        $import = new UsersImport();
        Excel::import($import, request()->file('registros'));
        return redirect(route('importar.index'))->with('success', 'Usuarios importados con exito!');
    }
    public function marcar()
    {
        $ahora = Carbon::now()->toDateString();
        $usuario = auth()->user()->id;
        $asistencia = Asistencia::where('user_id', $usuario)->whereDate('entrada', $ahora)->first();

        if ($asistencia == null) {
            Asistencia::create([
                'user_id' => $usuario,
                'entrada' => Carbon::now(),
            ]);
            return redirect(route('marcado'))->with('success', 'Marcaste tu entrada exitosamente!');
        } else {
            $asistencia->salida = Carbon::now();
            $asistencia->save();
            return redirect(route('marcado'))->with('success', 'Marcaste tu salida exitosamente!');
        }
    }

    public function marcado()
    {
        return view('admin.otros.marcado');
    }
    public function index()
    {
        return view('admin.otros.index');
    }
}
