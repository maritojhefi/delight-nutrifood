<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sucursale_id')->nullable();
            $table->foreign('sucursale_id')->references('id')->on('sucursales');
            $table->decimal('entrada')->unsigned()->nullable()->default(0);
            $table->decimal('acumulado')->unsigned()->nullable()->default(0);
            $table->string('estado')->nullable()->default('activo');
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
        Schema::dropIfExists('cajas');
    }
}
