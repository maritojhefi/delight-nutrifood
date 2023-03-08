<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeReciboImpresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recibo_impresos', function (Blueprint $table) {
            $table->longText('observacion')->nullable()->change();
            $table->longText('cliente')->nullable()->change();
            $table->longText('telefono')->nullable()->change();
            $table->longText('metodo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recibo_impresos', function (Blueprint $table) {
            $table->string('cliente')->nullable()->change();
            $table->string('cliente')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->string('metodo')->nullable()->change();
        });
    }
}
