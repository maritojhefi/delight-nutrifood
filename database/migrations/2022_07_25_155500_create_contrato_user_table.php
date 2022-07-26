<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratoUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_user', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('contrato_id')->unsigned();
            $table->time('entrada')->nullable()->default(null);
            $table->time('salida')->nullable()->default(null);
            $table->integer('diferencia_entrada')->nullable()->default(null);
            $table->integer('diferencia_salida')->nullable()->default(null);
            $table->integer('tiempo_total')->nullable()->default(null);
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
        Schema::dropIfExists('contrato_user');
    }
}
