<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPlanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->boolean('sopa')->default(true);
            $table->boolean('ensalada')->default(true);
            $table->boolean('segundo')->default(true);
            $table->boolean('carbohidrato')->default(true);
            $table->boolean('jugo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->dropColumn('sopa');
            $table->dropColumn('ensalada');
            $table->dropColumn('segundo');
            $table->dropColumn('carbohidrato');
            $table->dropColumn('jugo');
        });
    }
}
