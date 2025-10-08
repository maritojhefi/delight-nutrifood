<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSucursaleIdToMesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->foreignId('sucursale_id')->nullable()->constrained('sucursales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->dropForeign(['sucursale_id']);
            $table->dropColumn('sucursale_id');
        });
    }
}
