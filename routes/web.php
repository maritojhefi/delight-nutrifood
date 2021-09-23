<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/cliente', function () {
    return view('client.ajustes.index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//inicio
Route::prefix('/')->group(function () {
   
    Route::get('', [App\Http\Controllers\InicioController::class, 'index'])->name('inicio');
    

});
//ajustes
Route::prefix('/ajustes')->group(function () {
   
    Route::get('', [App\Http\Controllers\AjustesController::class, 'index'])->name('ajustes');
    

});
//productos
Route::prefix('/productos')->group(function () {
   
    Route::get('', [App\Http\Controllers\ProductoController::class, 'index'])->name('productos');
    

});
//promociones
Route::prefix('/promociones')->group(function () {
   
    Route::get('', [App\Http\Controllers\PromocionesController::class, 'index'])->name('promociones');
    

});
//perfil
Route::prefix('/miperfil')->group(function () {
   
    Route::get('', [App\Http\Controllers\MiperfilController::class, 'index'])->name('miperfil');
    

});


Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function(){
   
    Route::prefix('/inicio')->group(function () {
   
        Route::get('resumen', [App\Http\Controllers\admin\ProductosController::class, 'resumen'])->name('resumen');
        Route::get('ventas/sucursal', [App\Http\Controllers\admin\ProductosController::class, 'resumen'])->name('ventas.sucursal');
    
    });
    Route::prefix('/productos')->group(function () {
   
        Route::get('listar', [App\Http\Controllers\admin\ProductosController::class, 'listar'])->name('producto.listar');
        Route::get('crear', [App\Http\Controllers\admin\ProductosController::class, 'crear'])->name('producto.crear');
        Route::get('categoria', [App\Http\Controllers\admin\ProductosController::class, 'categoria'])->name('producto.categoria');
        Route::get('subcategoria', [App\Http\Controllers\admin\ProductosController::class, 'subcategoria'])->name('producto.subcategoria');
    });
    Route::prefix('/usuarios')->group(function () {
   
        Route::get('/index', \App\Http\Livewire\admin\Usuarios\UserIndex::class)->name('usuario.listar');
        Route::get('/roles', \App\Http\Livewire\admin\Usuarios\RolesIndex::class)->name('usuario.roles');
        Route::get('/miperfil', \App\Http\Livewire\admin\Usuarios\PerfilUsuario::class)->name('usuario.perfil');
    });
    Route::prefix('/sucursales')->group(function () {
   
        Route::get('/index', \App\Http\Livewire\admin\SucursalesIndex::class)->name('sucursal.listar');
        Route::get('/stock', \App\Http\Livewire\admin\StockProductos::class)->name('sucursal.stock');

    });
    Route::prefix('/ventas')->group(function () {
   
        Route::get('/index', \App\Http\Livewire\admin\Ventas\VentasIndex::class)->name('ventas.listar');

    });

});
