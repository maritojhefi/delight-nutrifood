<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetodoPagoIdToEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('egresos', function (Blueprint $table) {
            $table->foreignId('metodo_pago_id')->constrained('metodos_pagos');
            $table->decimal('monto', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('egresos', function (Blueprint $table) {
            //
            $table->dropForeign(['metodo_pago_id']);
            $table->dropColumn('metodo_pago_id');
            $table->dropColumn('monto');
        });
    }
}
