<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToHistorialVentaProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_venta_producto', function (Blueprint $table) {
            $table->double('precio_unitario', 15, 2)->nullable();
            $table->double('precio_subtotal', 15, 2)->nullable();
            $table->double('descuento_producto', 15, 2)->nullable();
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
            $table->dropColumn('precio_unitario');
            $table->dropColumn('precio_subtotal');
            $table->dropColumn('descuento_producto');
        });
    }
}
