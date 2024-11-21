<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToHistorialVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_ventas', function (Blueprint $table) {
            $table->double('subtotal', 15, 2)->nullable();
            $table->double('total_a_pagar', 15, 2)->nullable();
            $table->double('total_pagado', 15, 2)->nullable();
            $table->double('descuento_productos', 15, 2)->nullable();
            $table->double('descuento_manual', 15, 2)->nullable();
            $table->double('total_descuento', 15, 2)->nullable();
            $table->double('saldo_monto', 15, 2)->nullable();
            $table->boolean('a_favor_cliente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historial_ventas', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'total_pagado',
                'descuento_productos',
                'descuento_manual',
                'total_descuento',
                'saldo_monto',
                'a_favor_cliente',
            ]);
        });
    }
}
