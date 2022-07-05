<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCajaToSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saldos', function (Blueprint $table) {
            //$table->dropColumn('historial_venta_id');
            $table->text('detalle')->nullable();
            $table->integer('caja_id')->nullable();
            //$table->integer('historial_venta_id')->nullable();
            $table->integer('historial_venta_id_key')->nullable()->change();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saldos', function (Blueprint $table) {
            $table->dropColumn('detalle');
            $table->dropColumn('caja_id');
        });
    }
}
