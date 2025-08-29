<?php

namespace App\Observers;

use App\Helpers\GlobalHelper;
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
        GlobalHelper::cachearProductos();
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
