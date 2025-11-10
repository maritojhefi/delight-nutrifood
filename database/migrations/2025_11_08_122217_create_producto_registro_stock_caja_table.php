<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoRegistroStockCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_registro_stock_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_stock_caja_id')->constrained('registro_stock_cajas');
            $table->foreignId('producto_id')->constrained('productos');
            $table->string('accion'); //aumento o disminucion
            $table->integer('cantidad');
            $table->text('detalle');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto_registro_stock_caja');
    }
}
