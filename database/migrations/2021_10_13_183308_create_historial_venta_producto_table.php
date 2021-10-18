<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialVentaProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_venta_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('productos');
           
            $table->bigInteger('historial_venta_id')->unsigned()->nullable();
            $table->foreign('historial_venta_id')->references('id')->on('historial_ventas');
            $table->string('estado_actual', 10)->nullable()->default('finalizado');
            $table->integer('cantidad')->unsigned()->default(1);
            $table->string('adicionales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_venta_producto');
    }
}
