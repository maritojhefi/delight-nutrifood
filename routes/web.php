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
use App\Http\Livewire\Admin\PuntosRegistrosComponent;
use Illuminate\Support\Facades\Auth;

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
    Route::get('/subcategorias', [App\Http\Controllers\ProductoController::class, 'subcategorias'])->name('listar.subcategorias.productos');
    Route::get('/detalle/{id}', [App\Http\Controllers\ProductoController::class, 'detalleproducto'])->name('detalleproducto');
    Route::get('/subcategoria/{id}', [App\Http\Controllers\ProductoController::class, 'detallesubcategoria'])->name('listar.productos.subcategoria');
    Route::get('/categorizados/{id}', [App\Http\Controllers\ProductoController::class, 'productosSubcategoria']);
    Route::get('/add/carrito/{id}', [App\Http\Controllers\CarritoController::class, 'addToCarrito']);
    Route::get('/{id}/stock', [App\Http\Controllers\ProductoController::class, 'checkProductStock']);
    Route::get('/{id}', [App\Http\Controllers\ProductoController::class, 'getProduct']);
    Route::get('/tag/{id}', [App\Http\Controllers\ProductoController::class, 'getProductoTag']);
    Route::get('/limpiar-fotos/productos', [App\Http\Controllers\ProductoController::class, 'limpiarProductos']);
    Route::get('/buscar/{tipo}/{query}', [App\Http\Controllers\ProductoController::class, 'buscarProductos']);
    Route::get('/{id}/detallado', [App\Http\Controllers\ProductoController::class, 'getProductoDetalle']);
    Route::post('/validar-adicionales', [App\Http\Controllers\ProductoController::class, 'validarProductoAdicionales']);
});
//promociones
Route::prefix('/lineadelight')->group(function () {
    Route::get('', [App\Http\Controllers\LineaDelightController::class, 'index'])->name('linea.delight');
    Route::get('/populares', [App\Http\Controllers\LineaDelightController::class, 'lineadelightPopulares'])->name('delight.listar.populares');
    Route::get('/categoria/planes', [App\Http\Controllers\LineaDelightController::class, 'categoriaPlanes'])->name('categoria.planes');
    Route::get('/categorias/{horario}', [App\Http\Controllers\LineaDelightController::class, 'lineadelightHorario'])->name('delight.listar.subcategorias.horario');
    Route::get('/categoria/{id}', [App\Http\Controllers\ProductoController::class, 'lineadelightsubcategoria'])->name('delight.listar.productos.subcategoria');
    Route::get('/detalle/{id}', [App\Http\Controllers\ProductoController::class, 'lineadelightproducto'])->name('delight.detalleproducto');
});
Route::prefix('/carrito')
    ->middleware('auth')
    ->group(function () {
        Route::get('', action: [App\Http\Controllers\CarritoController::class, 'index'])->name('carrito');
        Route::post('mi-carrito', [App\Http\Controllers\CarritoController::class, 'validateCarrito']);
        Route::post('validar-item', [App\Http\Controllers\CarritoController::class, 'validateCartItem']);

        // Route::post('sincronizar', [App\Http\Controllers\CarritoController::class, 'validateCarrito']);
    });

Route::prefix('/ventas')
    ->middleware('auth')
    ->group(function () {
        Route::get('', [App\Http\Controllers\VentasCocinaController::class, 'index'])->name('ventas.cocina.pedido');
        Route::post('/ventaQR', [App\Http\Controllers\VentasWebController::class, 'generarVentaQR']);
        Route::post('/sincronizar', [App\Http\Controllers\VentasWebController::class, 'sincronizar_carrito']);
        Route::get('/productos', [App\Http\Controllers\VentasWebController::class, 'obtenerProductosVenta']);
        Route::get('/productos/{producto_venta_ID}', [App\Http\Controllers\VentasWebController::class, 'obtenerProductoVenta']);
        Route::post('/producto/observacion', [App\Http\Controllers\VentasWebController::class, 'actualizarObservacionVenta']);
        Route::patch('/producto/eliminar-orden', [App\Http\Controllers\VentasWebController::class, 'eliminarOrdenIndice']);
        Route::patch('/producto/disminuir-orden', [App\Http\Controllers\VentasWebController::class, 'disminuirProductoVenta']);
        Route::delete('/producto/{producto_venta_ID}', [App\Http\Controllers\VentasWebController::class, 'eliminarPedido']);
        Route::get('/producto/{producto_venta_id}/orden/{indice}', [App\Http\Controllers\VentasWebController::class, 'obtenerOrdenIndice']);
        Route::patch('/productos/actualizar-orden', [App\Http\Controllers\VentasWebController::class, 'actualizarOrdenIndice']);
    });
Route::post('/ventas/producto', [App\Http\Controllers\VentasWebController::class, 'agregarProductoVenta']);


Route::prefix('/otros')->group(function () {
    Route::get('/tutoriales', [App\Http\Controllers\OtrosController::class, 'tutorialesIndex'])->name('tutoriales');
    Route::get('/cambiarcolor', [App\Http\Controllers\OtrosController::class, 'cambiarColor']);
});
//perfil
Route::prefix('/miperfil')
    ->middleware('auth')
    ->group(function () {
        Route::get('/mostrar/{idplan}/{iduser}', [App\Http\Controllers\admin\UsuariosController::class, 'mostrar']);
        Route::get('/permiso/{id}/{todos}', [App\Http\Controllers\admin\UsuariosController::class, 'permiso']);
        Route::get('/editar/{id}', [App\Http\Controllers\admin\UsuariosController::class, 'editar']);
        Route::get('/saldo/usuario', [App\Http\Controllers\admin\UsuariosController::class, 'saldo'])->name('usuario.saldo');
        Route::get('/saldo/historial', [App\Http\Controllers\admin\UsuariosController::class, 'saldoHistorial']);
        Route::get('', [App\Http\Controllers\MiperfilController::class, 'index'])->name('miperfil');
        Route::get('/calendario/{plan}/{usuario}', [App\Http\Controllers\MiperfilController::class, 'calendario'])->name('calendario.cliente');
        Route::post('/personalizardia', [App\Http\Controllers\MiperfilController::class, 'personalizardia'])->name('personalizardia');
        Route::get('/editardia/{idpivot}', [App\Http\Controllers\MiperfilController::class, 'editardia'])->name('editardia');
        Route::post('/subirfoto', [App\Http\Controllers\MiperfilController::class, 'subirFoto'])->name('subirfoto.perfil');
        Route::get('/misplanes', [App\Http\Controllers\MiperfilController::class, 'misPlanes'])->name('misplanes');
        Route::get('/whatsapp/asistente', [App\Http\Controllers\MiperfilController::class, 'revisarWhatsappAsistente']);
        Route::get('/whatsapp/cambiar/estado', [App\Http\Controllers\MiperfilController::class, 'cambiarEstadoWhatsappAsistente']);


        Route::get('/enlace/patrocinador/{id}', [App\Http\Controllers\MiperfilController::class, 'enlacePatrocinador'])->name('enlace.patrocinador');

        //rutas para completar perfil ajax
        Route::post('/change/birthday', [App\Http\Controllers\MiperfilController::class, 'actualizarNacimiento']);
    });
Route::get('perfil/editar', [App\Http\Controllers\MiperfilController::class, 'revisarPerfil'])
    ->middleware('auth')
    ->name('llenarDatosPerfil');
Route::post('perfil/guardarPerfilFaltante', [
    App\Http\Controllers\MiperfilController::class,
    'guardar
PerfilFaltante',
])
    ->middleware('auth')
    ->name('guardarPerfilFaltante');

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
        Route::get('tags', \App\Http\Livewire\Admin\Productos\TagsComponent::class)->name('producto.tags');
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
            DB::table('plane_user')
                ->where('estado', 'despachado')
                ->update(['estado' => 'pendiente']);
        });
    });
    Route::prefix('/convenios')->group(function () {
        Route::get('/index', \App\Http\Livewire\Admin\ConveniosIndexComponent::class)->name('convenio.index');
        Route::get('/vincular/clientes', \App\Http\Livewire\Admin\ConveniosUsuariosComponent::class)->name('convenio.vincular.usuario');
        Route::get('/usuarios-disponibles/{convenioId}', [\App\Http\Livewire\Admin\ConveniosUsuariosComponent::class, 'getUsuariosDisponibles'])->name('convenio.usuarios.disponibles');
    });
    Route::prefix('/sucursales')->group(function () {
        Route::get('/index', \App\Http\Livewire\Admin\SucursalesIndex::class)->name('sucursal.listar');
        Route::get('/stock', \App\Http\Livewire\Admin\StockProductos::class)->name('sucursal.stock');
    });
    Route::prefix('/ventas')->group(function () {
        Route::get('/index', \App\Http\Livewire\Admin\Ventas\VentasIndex::class)->middleware('checkCajaOpen')->name('ventas.listar');
        Route::get('/prospectos', \App\Http\Livewire\Admin\Ventas\ProspectosComponent::class)->name('ventas.prospectos');
        Route::get('/mesas', \App\Http\Livewire\Admin\Ventas\MesasComponent::class)->name('ventas.mesas');
    });
    Route::prefix('/almuerzos')->group(function () {
        Route::get('/index', \App\Http\Livewire\Admin\Almuerzos\Personalizar::class)->name('almuerzos.listar');
        Route::get('/reporte', \App\Http\Livewire\Admin\Almuerzos\ReporteDiario::class)->name('almuerzos.reporte');
        Route::get('/reporte/semana', \App\Http\Livewire\Admin\Almuerzos\ReporteSemanal::class)->name('reporte.semana');
        Route::get('/reporte/cocina', \App\Http\Livewire\Admin\Almuerzos\CocinaDespachePlanes::class)->name('reporte.cocina');
        Route::get('/reporte/whatsapp', \App\Http\Livewire\Admin\WhatsappReporteDesarrolloComponent::class)->name('reporte.whatsapp');
        Route::get('/nutribar', \App\Http\Livewire\Admin\Almuerzos\NutriBarPanelComponent::class)->name('nutribar.index');
    });
    Route::prefix('/perifericos')->group(function () {
        Route::get('/impresoras', \App\Http\Livewire\Perifericos\ImpresorasIndex::class)->name('impresoras.index');
    });
    Route::prefix('/caja')->group(function () {
        Route::get('/reporte/ventas/v2', \App\Http\Livewire\Admin\Caja\ReporteVentas::class)->name('caja.reportes.v2');
        Route::get('/reporte/mensual', \App\Http\Livewire\Admin\Caja\ReporteMensual::class)->name('caja.reporte.mensual');
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
    Route::prefix('/configuraciones')->group(function () {
        Route::get('/sistema', \App\Http\Livewire\Admin\Configuracion\SistemaIndexComponent::class)->name('sistema.index');
    });

    Route::prefix('/horarios')->group(function () {
        Route::get('/index', \App\Http\Livewire\Admin\Horarios\IndexComponent::class)->name('index.horarios');
    });

    Route::prefix('/puntos')->group(function () {
        Route::get('/perfiles/index', \App\Http\Livewire\Admin\PuntosPerfilesComponent::class)->name('perfiles.index');
        Route::get('/registros/index', PuntosRegistrosComponent::class)->name('registros.index');
    });
});

Route::get('/construccion', function () {
    return view('client.page-construccion');
})->name('construccion');

Route::prefix('/pedidos')->group(function () {
    Route::get('/inicio', [PedidosController::class, 'index']);
});

Route::prefix('/usuario')
    ->name('usuario.')
    ->group(function () {
        Route::get('/registro', [UsuarioController::class, 'inicioRegistro'])->name('inicio.registro');
        Route::post('/validar-paso', [UsuarioController::class, 'validarPaso'])->name('validar-paso');
        Route::post('/registrar', [UsuarioController::class, 'registrarUsuario'])->name('registrar');
        Route::post('/verificar', [UsuarioController::class, 'verificarUsuario'])->name('existe');

        Route::post('/verificar-numero', [UsuarioController::class, 'verificarNumero'])->name('verificar-numero');
        Route::post('/enviar-codigo-verificacion', [UsuarioController::class, 'enviarCodigoVerificacion'])->name('enviar-codigo-verificacion');
        Route::post('/verificar-codigo-otp', [UsuarioController::class, 'verificarCodigoOTP'])->name('verificar-codigo-otp');

        Route::get('/actualizado', function () {
            return view('auth.registrado');
        });
        Route::get('/reconocer/cliente/{idEncriptado}', [UsuarioController::class, 'reconocerUsuarioNFC'])->name('reconocer-usuario-nfc');
    });
