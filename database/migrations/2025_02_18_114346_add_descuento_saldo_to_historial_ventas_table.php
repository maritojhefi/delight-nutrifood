<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescuentoSaldoToHistorialVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_ventas', function (Blueprint $table) {
            $table->double('descuento_saldo', 15, 2)->after('descuento_productos')->unsigned()->nullable();
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
            $table->dropColumn('descuento_saldo');
        });
    }
}
