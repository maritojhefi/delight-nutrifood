<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEsDefaultToAreasDespachosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('areas_despachos', function (Blueprint $table) {
            $table->boolean('es_default')->default(false)->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('areas_despachos', function (Blueprint $table) {
            $table->dropColumn('es_default');
        });
    }
}

