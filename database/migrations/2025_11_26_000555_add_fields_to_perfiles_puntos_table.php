<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPerfilesPuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perfiles_puntos', function (Blueprint $table) {
            $table->boolean('default')->after('bono')->default(false);
            $table->boolean('bloqueado')->after('default')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfiles_puntos', function (Blueprint $table) {
            $table->dropColumn('default');
            $table->dropColumn('bloqueado');
        });
    }
}
