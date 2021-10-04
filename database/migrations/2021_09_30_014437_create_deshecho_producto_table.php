<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeshechoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deshecho_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('deshecho_id')->nullable();
            $table->foreign('deshecho_id')->references('id')->on('deshechos');
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
        Schema::dropIfExists('deshecho_producto');
    }
}
