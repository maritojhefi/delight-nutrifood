<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetodosPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metodos_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_metodo_pago');
            $table->string('codigo');
            $table->foreignId('sucursal_id')->constrained('sucursales');
            $table->string('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'MetodosPagosSeeder',
        ]);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metodos_pagos');
    }
}
