<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saldos', function (Blueprint $table) {
            $table->dateTime('liquidado')->after('monto')->nullable();
            $table->decimal('saldo_restante', 10, 2)->default(0)->after('liquidado'); // Lo que queda pendiente de pagar/liquidar
        });
        Artisan::call('saldos:liquidar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saldos', function (Blueprint $table) {
            $table->dropColumn(['liquidado','saldo_restante']);
        });
    }
}
