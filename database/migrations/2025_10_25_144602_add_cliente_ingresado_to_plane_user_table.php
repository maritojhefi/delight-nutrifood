<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClienteIngresadoToPlaneUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plane_user', function (Blueprint $table) {
            $table->boolean('cliente_ingresado')->default(false);
            $table->dateTime('cliente_ingresado_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plane_user', function (Blueprint $table) {
            $table->dropColumn('cliente_ingresado');
            $table->dropColumn('cliente_ingresado_at');
        });
    }
}
