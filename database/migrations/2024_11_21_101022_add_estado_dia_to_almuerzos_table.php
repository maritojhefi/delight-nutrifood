<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoDiaToAlmuerzosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('almuerzos', function (Blueprint $table) {
            $table->boolean('estado_dia')->after('dia')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('almuerzos', function (Blueprint $table) {
            $table->dropColumn('estado_dia');
        });
    }
}
