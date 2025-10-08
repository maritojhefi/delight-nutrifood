<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescuentoConvenioToHistorialVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_ventas', function (Blueprint $table) {
            $table->decimal('descuento_convenio', 10, 2)->nullable()->unsigned()->default(0);
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
            $table->dropColumn('descuento_convenio');
        });
    }
}
