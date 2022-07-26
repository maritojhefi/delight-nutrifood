<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->date('fecha_inicio')->default(date('Y-m-d'));
            $table->string('observacion', 100)->nullable();
            $table->time('hora_entrada');
            $table->time('hora_salida');
            $table->boolean('lunes')->default(true);
            $table->boolean('martes')->default(true);
            $table->boolean('miercoles')->default(true);
            $table->boolean('jueves')->default(true);
            $table->boolean('viernes')->default(true);
            $table->boolean('sabado')->default(true);
            $table->boolean('domingo')->default(false);
            $table->integer('sueldo')->unsigned()->nullable()->default(12);
            $table->string('modalidad', 20)->nullable()->default;
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
        Schema::dropIfExists('contratos');
    }
}
