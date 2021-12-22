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
            $table->boolean('sopa_estado')->nullable()->default(true);
            $table->string('ensalada', 100)->nullable();
            $table->boolean('ensalada_estado')->nullable()->default(true);
            $table->string('ejecutivo', 100)->nullable();
            $table->boolean('ejecutivo_estado')->nullable()->default(true);
            $table->string('dieta', 100)->nullable();
            $table->boolean('dieta_estado')->nullable()->default(true);
            $table->string('vegetariano', 100)->nullable();
            $table->boolean('vegetariano_estado')->nullable()->default(true);
            $table->string('carbohidrato_1', 100)->nullable();
            $table->boolean('carbohidrato_1_estado')->nullable()->default(true);
            $table->string('carbohidrato_2', 100)->nullable();
            $table->boolean('carbohidrato_2_estado')->nullable()->default(true);
            $table->string('carbohidrato_3', 100)->nullable();
            $table->boolean('carbohidrato_3_estado')->nullable()->default(true);
            $table->string('jugo', 100)->nullable();
            $table->boolean('jugo_estado')->nullable()->default(true);
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
