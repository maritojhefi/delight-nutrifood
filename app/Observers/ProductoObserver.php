<?php

namespace App\Observers;

use App\Models\Producto;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductoObserver
{
    /**
     * Handle the Producto "created" event.
     *
     * @param  \App\Models\Producto  $producto
     * @return void
     */
    public function created(Producto $producto)
    {
        $this->cachearProductos();
    }

    /**
     * Handle the Producto "updated" event.
     *
     * @param  \App\Models\Producto  $producto
     * @return void
     */
    public function updated(Producto $producto)
    {
        $this->cachearProductos();
    }

    /**
     * Handle the Producto "deleted" event.
     *
     * @param  \App\Models\Producto  $producto
     * @return void
     */
    public function deleted(Producto $producto)
    {
        $this->cachearProductos();
    }
    public function cachearProductos()
    {
        Cache::forget('productos'); // Retirar el viejo valor de cache

        // Cacheado de los productos disponibles, incluyendo unicamente la informacion mas relevante
        Cache::remember('productos', 60, function () {
            return Producto::select([
                    'id',
                    'nombre', 
                    'precio',
                    'descuento',
                    'subcategoria_id',
                    'imagen',
                ])
                ->with([
                    // Inclusion de registros relacionados necesarios para el manejo comun de los productos
                    'subcategoria:id,nombre,categoria_id',
                    'subcategoria.categoria:id,nombre',
                    'tag:id,icono'
                ])
                ->get();
        });
    }

    /**
     * Handle the Producto "restored" event.
     *
     * @param  \App\Models\Producto  $producto
     * @return void
     */
    public function restored(Producto $producto)
    {
        //
    }

    /**
     * Handle the Producto "force deleted" event.
     *
     * @param  \App\Models\Producto  $producto
     * @return void
     */
    public function forceDeleted(Producto $producto)
    {
        //
    }
}
