<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNroTicketToProductoVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_venta', function (Blueprint $table) {
            $table->longText('nro_ticket')->after('observacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('producto_venta', function (Blueprint $table) {
            $table->dropColumn('nro_ticket');
        });
    }
}
