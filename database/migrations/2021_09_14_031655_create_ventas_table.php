<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->unsignedBigInteger('sucursale_id')->nullable();
            $table->foreign('sucursale_id')->references('id')->on('sucursales');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('users');
            $table->integer('total')->nullable()->unsigned()->default(0);
            $table->integer('puntos')->nullable()->unsigned()->default(0);
            $table->integer('descuento')->nullable()->unsigned()->default(0);
            $table->string('tipo', 100)->nullable();
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
        Schema::dropIfExists('ventas');
    }
}
