<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combo_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('combo_id')->nullable();
            $table->foreign('combo_id')->references('id')->on('combos');
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
        Schema::dropIfExists('combo_producto');
    }
}
