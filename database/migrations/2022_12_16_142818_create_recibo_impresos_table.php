<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReciboImpresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibo_impresos', function (Blueprint $table) {
            $table->id();
            $table->integer('venta_id')->unsigned()->nullable();
            $table->integer('historial_venta_id')->unsigned()->nullable();
            $table->string('observacion')->nullable();
            $table->string('cliente')->nullable();
            $table->string('telefono')->nullable();
            $table->dateTime('fecha')->nullable();
            $table->string('metodo')->nullable();
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
        Schema::dropIfExists('recibo_impresos');
    }
}
