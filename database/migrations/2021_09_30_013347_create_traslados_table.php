<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traslados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('sucursale_id')->nullable();
            $table->foreign('sucursale_id')->references('id')->on('sucursales');
            $table->unsignedBigInteger('destino_id')->nullable();
            $table->foreign('destino_id')->references('id')->on('sucursales');
            $table->integer('cantidad')->unsigned()->nullable();
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
        Schema::dropIfExists('traslados');
    }
}
