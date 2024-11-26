<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetodoPagoSaldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metodo_pago_saldo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saldo_id')->constrained('saldos');
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
        Schema::dropIfExists('metodo_pago_saldo');
    }
}
