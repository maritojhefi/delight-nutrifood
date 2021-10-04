<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdicionaleSubcategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adicionale_subcategoria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adicionale_id')->nullable();
            $table->foreign('adicionale_id')->references('id')->on('adicionales');
            $table->unsignedBigInteger('subcategoria_id')->nullable();
            $table->foreign('subcategoria_id')->references('id')->on('subcategorias');
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
        Schema::dropIfExists('adicionale_subcategoria');
    }
}
