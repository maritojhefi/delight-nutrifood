<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoToTutorialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutoriales', function (Blueprint $table) {
            $table->string('tipo');
            $table->longText('url')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutoriales', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->string('url')->change();
        });
    }
}
