<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialVentaMetodoPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_venta_metodo_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historial_venta_id')->constrained('historial_ventas');
            $table->foreignId('metodo_pago_id')->constrained('metodos_pagos');
            $table->double('monto', 15, 2);
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
        Schema::dropIfExists('historial_venta_metodo_pago');
    }
}
