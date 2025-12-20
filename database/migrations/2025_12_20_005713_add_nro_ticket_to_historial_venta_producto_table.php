<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNroTicketToHistorialVentaProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_venta_producto', function (Blueprint $table) {
            $table->longText('nro_ticket')->after('historial_venta_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historial_venta_producto', function (Blueprint $table) {
            $table->dropColumn('nro_ticket');
        });
    }
}
