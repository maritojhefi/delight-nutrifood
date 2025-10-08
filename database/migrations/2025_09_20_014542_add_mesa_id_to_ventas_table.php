<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMesaIdToVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId('mesa_id')->nullable()->constrained('mesas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['mesa_id']);
            $table->dropColumn('mesa_id');
        });
    }
}
