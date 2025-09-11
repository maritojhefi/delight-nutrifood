<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroPuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros_puntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historial_venta_id')->nullable()->constrained('historial_ventas');
            $table->foreignId('partner_id')->nullable()->constrained('users');
            $table->foreignId('cliente_id')->constrained('users');
            $table->string('tipo');
            $table->integer('puntos_partner');
            $table->integer('puntos_cliente');
            $table->integer('total_puntos');
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
        Schema::dropIfExists('registros_puntos');
    }
}
