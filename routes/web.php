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


Route::prefix('/admin')->group(function () {
   
    Route::prefix('/inicio')->group(function () {
   
        Route::get('resumen', [App\Http\Controllers\Admin\ProductosController::class, 'resumen'])->name('resumen');
        Route::get('ventas/sucursal', [App\Http\Controllers\Admin\ProductosController::class, 'resumen'])->name('ventas.sucursal');
    
    });
    

});
