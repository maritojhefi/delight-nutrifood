<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToProductoVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_venta', function (Blueprint $table) {
            $table->decimal('descuento_convenio', 10, 2)->after('cantidad')->nullable()->unsigned()->default(0);
            $table->decimal('descuento_producto', 10, 2)->after('descuento_convenio')->nullable()->unsigned()->default(0);
            $table->decimal('total', 10, 2)->after('descuento_producto')->nullable()->unsigned()->default(0);
            $table->decimal('total_adicionales', 10, 2)->after('adicionales')->nullable()->unsigned()->default(0);
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
            $table->dropColumn('descuento_convenio');
            $table->dropColumn('descuento_producto');
            $table->dropColumn('total');
            $table->dropColumn('total_adicionales');
        });
    }
}
