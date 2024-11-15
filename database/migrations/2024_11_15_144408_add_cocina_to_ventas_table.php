<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCocinaToVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->boolean('cocina')->default(false);
            $table->dateTime('cocina_at')->nullable();
            $table->boolean('despachado_cocina')->default(false);
            $table->integer('historial_venta_id')->nullable();
            $table->string('tipo_entrega')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('cocina');
            $table->dropColumn('cocina_at');
            $table->dropColumn('despachado_cocina');
            $table->dropColumn('historial_venta_id');
            $table->dropColumn('tipo_entrega');
        });
    }
}
