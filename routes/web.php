<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Rawilk\Printing\Facades\Printing;
use App\Http\Livewire\Client\Inicio\Index;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\admin\UsuariosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/asd', function () {
    dd(Crypt::decrypt('eyJpdiI6Ijc1bXZ6Vmt2YnZwSGZkOHdIeXR2Mnc9PSIsInZhbHVlIjoiWXB5TFJRcFQybEhjQlhqSU5jRnRkdz09IiwibWFjIjoiMmI1Y2ZkNTgzYjNkNWYyYWQ5M2QzZTE3ZjYyZDQ5ZDE2ZjBkOTc2ZTEyYmRjYTIxZjE2NzVmMGYxNDRkNDc2MSIsInRhZyI6IiJ9'));
});

Route::get('/cliente', function () {
    return view('client.ajustes.index');
});

Auth::routes();

Route::get('/login/withid/{id}', [App\Http\Controllers\UsuarioController::class, 'loginWithId']);


Route::get('/', function () {
    return redirect(route('inicio'));
})->name('home');

//inicio

Route::prefix('/inicio')->group(function () {


    Route::get('', [App\Http\Controllers\ProductoController::class, 'menusemanal'])->name('inicio');
    Route::get('menusemanal', [App\Http\Controllers\ProductoController::class, 'menusemanal'])->name('menusemanal');
});
//ajustes
Route::prefix('/ajustes')->group(function () {

    Route::get('', [App\Http\Controllers\AjustesController::class, 'index'])->name('ajustes');
});

//404
Route::prefix('/404')->group(function () {

    Route::get('/login', function () {
        return view('client.404.error-login');
    })->name('errorLogin');
});
//productos
Route::prefix('/productos')->group(function () {

    Route::get('', [App\Http\Controllers\ProductoController::class, 'index'])->name('productos');
    Route::get('/detalle/{id}', [App\Http\Controllers\ProductoController::class, 'detalleproducto'])->name('detalleproducto');
    Route::get('/subcategoria/{id}', [App\Http\Controllers\ProductoController::class, 'detallesubcategoria'])->name('listar.productos.subcategoria');



    Route::get('/add/carrito/{id}', [App\Http\Controllers\CarritoController::class, 'addToCarrito']);
});
//promociones
Route::prefix('/lineadelight')->group(function () {

    Route::get('', [App\Http\Controllers\LineaDelightController::class, 'index'])->name('linea.delight');
    Route::get('/categoria/planes', [App\Http\Controllers\LineaDelightController::class, 'categoriaPlanes'])->name('categoria.planes');
    Route::get('/lineadelight/{id}', [App\Http\Controllers\ProductoController::class, 'lineadelightsubcategoria'])->name('delight.listar.productos.subcategoria');
    Route::get('/lineadelight/detalle/{id}', [App\Http\Controllers\ProductoController::class, 'lineadelightproducto'])->name('delight.detalleproducto');
});
Route::prefix('/carrito')->middleware('auth')->group(function () {

    Route::get('', [App\Http\Controllers\CarritoController::class, 'index'])->name('carrito');
});

Route::prefix('/ventas')->middleware('auth')->group(function () {

    Route::get('', [App\Http\Controllers\VentasCocinaController::class, 'index'])->name('ventas.cocina.pedido');
});
Route::prefix('/otros')->group(function () {

    Route::get('/tutoriales', [App\Http\Controllers\OtrosController::class, 'tutorialesIndex'])->name('tutoriales');
    Route::get('/cambiarcolor', [App\Http\Controllers\OtrosController::class, 'cambiarColor']);
});
//perfil
Route::prefix('/miperfil')->middleware('auth')->group(function () {
    Route::get('/mostrar/{idplan}/{iduser}', [App\Http\Controllers\admin\UsuariosController::class, 'mostrar']);
    Route::get('/permiso/{id}/{todos}', [App\Http\Controllers\admin\UsuariosController::class, 'permiso']);
    Route::get('/editar/{id}', [App\Http\Controllers\admin\UsuariosController::class, 'editar']);
    Route::get('/saldo/usuario', [App\Http\Controllers\admin\UsuariosController::class, 'saldo'])->name('usuario.saldo');
    Route::get('', [App\Http\Controllers\MiperfilController::class, 'index'])->name('miperfil');
    Route::get('/calendario/{plan}/{usuario}', [App\Http\Controllers\MiperfilController::class, 'calendario'])->name('calendario.cliente');
    Route::post('/personalizardia', [App\Http\Controllers\MiperfilController::class, 'personalizardia'])->name('personalizardia');
    Route::get('/editardia/{idpivot}', [App\Http\Controllers\MiperfilController::class, 'editardia'])->name('editardia');
    Route::post('/subirfoto', [App\Http\Controllers\MiperfilController::class, 'subirFoto'])->name('subirfoto.perfil');
    Route::get('/misplanes', [App\Http\Controllers\MiperfilController::class, 'misPlanes'])->name('misplanes');
    Route::get('/whatsapp/asistente', [App\Http\Controllers\MiperfilController::class, 'revisarWhatsappAsistente']);
    Route::get('/whatsapp/cambiar/estado', [App\Http\Controllers\MiperfilController::class, 'cambiarEstadoWhatsappAsistente']);

    //rutas para completar perfil ajax
    Route::post('/change/birthday', [App\Http\Controllers\MiperfilController::class, 'actualizarNacimiento']);
});
Route::get('perfil/editar', [App\Http\Controllers\MiperfilController::class, 'revisarPerfil'])->middleware('auth')->name('llenarDatosPerfil');
Route::post('perfil/guardarPerfilFaltante', [App\Http\Controllers\MiperfilController::class, 'guardar
PerfilFaltante'])->middleware('auth')->name('guardarPerfilFaltante');


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'checkrol']], function () {

    Route::prefix('/inicio')->group(function () {

        Route::get('resumen', [App\Http\Controllers\admin\ProductosController::class, 'resumen'])->name('resumen');
        Route::get('ventas/sucursal', [App\Http\Controllers\admin\ProductosController::class, 'resumen'])->name('ventas.sucursal');
    });
    Route::prefix('/tienda')->group(function () {

        Route::get('galeria', \App\Http\Livewire\Admin\TiendaComponent::class)->name('tienda.galeria');
        Route::get('novedades', \App\Http\Livewire\Admin\NovedadesVideosComponent::class)->name('tienda.novedades');
        Route::get('tutoriales', \App\Http\Livewire\Admin\TutorialesComponent::class)->name('tienda.tutoriales');
    });
    Route::prefix('/productos')->group(function () {

        Route::get('listar', [App\Http\Controllers\admin\ProductosController::class, 'listar'])->name('producto.listar');
        Route::get('crear', [App\Http\Controllers\admin\ProductosController::class, 'crear'])->name('producto.crear');
        Route::get('categoria', [App\Http\Controllers\admin\ProductosController::class, 'categoria'])->name('producto.categoria');
        Route::get('subcategoria', [App\Http\Controllers\admin\ProductosController::class, 'subcategoria'])->name('producto.subcategoria');
        Route::get('adicionales', \App\Http\Livewire\Admin\Productos\Adicionales::class)->name('producto.adicionales');
        Route::get('agregar/adicional', \App\Http\Livewire\Admin\Productos\AgregarAdicional::class)->name('producto.agregar.adicional');
        Route::get('productos/expiracion', \App\Http\Livewire\Admin\Productos\ProductosPorExpirar::class)->name('producto.expiracion');
    });
    Route::prefix('/usuarios')->group(function () {

        Route::get('/index', \App\Http\Livewire\Admin\Usuarios\UserIndex::class)->name('usuario.listar');
        Route::get('/roles', \App\Http\Livewire\Admin\Usuarios\RolesIndex::class)->name('usuario.roles');
        Route::get('/miperfil', \App\Http\Livewire\Admin\Usuarios\PerfilUsuario::class)->name('usuario.perfil');
        Route::get('/pensionados', \App\Http\Livewire\Admin\Usuarios\Pensionados::class)->name('pensionados');
        Route::get('/planes', \App\Http\Livewire\Admin\Usuarios\Planes::class)->name('planes');
        Route::get('/planes/expirar', \App\Http\Livewire\Admin\Usuarios\PlanesPorExpirar::class)->name('planes.expirar');
        Route::get('/personal', \App\Http\Livewire\Admin\Usuarios\Personal::class)->name('personal');
        Route::get('/empleos', \App\Http\Livewire\Admin\Usuarios\EmpleoCreate::class)->name('usuario.empleo');
        Route::get('/crear/plan', \App\Http\Livewire\Admin\Usuarios\CrearPlan::class)->name('crear.plan');
        Route::get('/saldos/usuarios', \App\Http\Livewire\Admin\Usuarios\SaldosComponent::class)->name('usuario.saldos');
        Route::get('/cumpleanos/usuarios', \App\Http\Livewire\Admin\Usuarios\CumpleanosComponent::class)->name('usuario.cumpleanos');
        Route::get('plan/{id}/{planid}', [App\Http\Controllers\admin\UsuariosController::class, 'editarPlanUsuario'])->name('calendario.usuario');

        Route::get('detalleplan/{id}/{planid}', [App\Http\Controllers\admin\UsuariosController::class, 'detalleplan'])->name('detalleplan');
        Route::post('/agregarplan', [App\Http\Controllers\admin\UsuariosController::class, 'agregar']);
        Route::post('/feriado', [App\Http\Controllers\admin\UsuariosController::class, 'feriado']);
        Route::get('/archivar/{id}', [App\Http\Controllers\admin\UsuariosController::class, 'archivar']);
        Route::get('/permiso/{id}/{todos}', [App\Http\Controllers\admin\UsuariosController::class, 'permiso']);
        Route::get('/quitarpermiso/{id}', [App\Http\Controllers\admin\UsuariosController::class, 'quitarpermiso']);
        Route::get('/editar/{id}', [App\Http\Controllers\admin\UsuariosController::class, 'editar']);
        Route::get('/borrar/{id}', [App\Http\Controllers\admin\UsuariosController::class, 'borrar']);


        Route::get('/mostrar/{idplan}/{iduser}', [App\Http\Controllers\admin\UsuariosController::class, 'mostrar']);

        Route::get('/asistencia', \App\Http\Livewire\Admin\Usuarios\AsistenciaPersonal::class)->name('usuario.asistencia');
        Route::get('/cambiarEstados', function () {
            DB::table('plane_user')->where('estado', 'despachado')->update(['estado' => 'pendiente']);
        });
    });
    Route::prefix('/sucursales')->group(function () {

        Route::get('/index', \App\Http\Livewire\Admin\SucursalesIndex::class)->name('sucursal.listar');
        Route::get('/stock', \App\Http\Livewire\Admin\StockProductos::class)->name('sucursal.stock');
    });
    Route::prefix('/ventas')->group(function () {

        Route::get('/index', \App\Http\Livewire\Admin\Ventas\VentasIndex::class)->middleware('checkCajaOpen')->name('ventas.listar');
        Route::get('/prospectos', \App\Http\Livewire\Admin\Ventas\ProspectosComponent::class)->name('ventas.prospectos');
    });
    Route::prefix('/almuerzos')->group(function () {

        Route::get('/index', \App\Http\Livewire\Admin\Almuerzos\Personalizar::class)->name('almuerzos.listar');
        Route::get('/reporte', \App\Http\Livewire\Admin\Almuerzos\ReporteDiario::class)->name('almuerzos.reporte');
        Route::get('/reporte/semana', \App\Http\Livewire\Admin\Almuerzos\ReporteSemanal::class)->name('reporte.semana');
        Route::get('/reporte/cocina', \App\Http\Livewire\Admin\Almuerzos\CocinaDespachePlanes::class)->name('reporte.cocina');
        Route::get('/reporte/whatsapp', \App\Http\Livewire\Admin\WhatsappReporteDesarrolloComponent::class)->name('reporte.whatsapp');
    });
    Route::prefix('/perifericos')->group(function () {

        Route::get('/impresoras', \App\Http\Livewire\Perifericos\ImpresorasIndex::class)->name('impresoras.index');
    });
    Route::prefix('/caja')->group(function () {
        Route::get('/reporte/ventas/v2', \App\Http\Livewire\Admin\Caja\ReporteVentas::class)->name('caja.reportes.v2');
        Route::get('/diaria', \App\Http\Livewire\Admin\Caja\CajaDiaria::class)->name('caja.diaria');
        Route::get('/reportes', \App\Http\Livewire\Admin\Caja\Reportes::class)->name('caja.reportes');
    });
    Route::prefix('/otros')->group(function () {

        Route::post('importar', [App\Http\Controllers\admin\OtroController::class, 'importar'])->name('importar.excel');
        Route::post('importar/usuarios', [App\Http\Controllers\admin\OtroController::class, 'importarUser'])->name('importarUser.excel');
        Route::get('index', [App\Http\Controllers\admin\OtroController::class, 'index'])->name('importar.index');
        Route::get('marcar', [App\Http\Controllers\admin\OtroController::class, 'marcar'])->name('marcar.asistencia');
        Route::post('registrar/asistencia', [App\Http\Controllers\admin\OtroController::class, 'marcarAsistencia'])->name('registrar.asistencia');
        Route::get('/marcado', function () {
            return view('admin.otros.marcado');
        })->name('marcado');
        Route::get('/whatsapp/tickets', \App\Http\Livewire\Admin\WhatsappTicket::class)->name('whatsapp.index');
        Route::get('/whatsapp/historial', \App\Http\Livewire\Admin\WhatsappHistorialComponent::class)->name('whatsapp.historial');
        Route::get('/noEsEmpleado', function () {
            return view('admin.otros.no-es-empleado');
        })->name('noEsEmpleado');
        Route::get('/registrado/exitosamente/{diferencia}', function ($diferencia) {
            return view('admin.otros.marcado-exitosamente', compact('diferencia'));
        })->name('marcacion.entrada');
        Route::get('/registrado/salida/{diferencia}', function ($diferencia) {
            return view('admin.otros.marcado-exitosamente-salida', compact('diferencia'));
        })->name('marcacion.salida');
    });
});

Route::get('/construccion', function () {
    return view('client.page-construccion');
})->name('construccion');

Route::prefix('/pedidos')->group(function () {
    Route::get('/inicio', [PedidosController::class, 'index']);
});

Route::prefix('/usuario')->name('usuario.')->group(function () {
    Route::get('/registro', [UsuarioController::class, 'inicioRegistro'])->name('inicio.registro');
    Route::post('/registrar', [UsuarioController::class, 'registrarUsuario'])->name('registrar');
    Route::post('/verificar', [UsuarioController::class, 'verificarUsuario'])->name('existe');
    Route::get('/actualizado', function(){
        return view('auth.registrado');
    });
    Route::get('/reconocer/cliente/{idEncriptado}', [UsuarioController::class, 'reconocerUsuarioNFC'])->name('reconocer-usuario-nfc');
});
