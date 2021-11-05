<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlmuerzosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('almuerzos', function (Blueprint $table) {
            $table->id();
            $table->string('dia', 100)->nullable();
            $table->string('sopa', 100)->nullable();
            $table->string('ensalada', 100)->nullable();
            $table->string('ejecutivo', 100)->nullable();
            $table->string('dieta', 100)->nullable();
            $table->string('vegetariano', 100)->nullable();
            $table->string('carbohidrato_1', 100)->nullable();
            $table->string('carbohidrato_2', 100)->nullable();
            $table->string('carbohidrato_3', 100)->nullable();
            $table->string('jugo', 100)->nullable();
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
        Schema::dropIfExists('almuerzos');
    }
}
