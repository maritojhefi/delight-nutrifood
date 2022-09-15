<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCantidadesToAlmuerzosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('almuerzos', function (Blueprint $table) {
            $table->integer('sopa_cant')->unsigned()->nullable()->after('sopa');
            $table->integer('ensalada_cant')->unsigned()->nullable()->after('ensalada');
            $table->integer('ejecutivo_cant')->unsigned()->nullable()->after('ejecutivo');
            $table->integer('dieta_cant')->unsigned()->nullable()->after('dieta');
            $table->integer('vegetariano_cant')->unsigned()->nullable()->after('vegetariano');
            $table->integer('carbohidrato_1_cant')->unsigned()->nullable()->after('carbohidrato_1');
            $table->integer('carbohidrato_2_cant')->unsigned()->nullable()->after('carbohidrato_2');
            $table->integer('carbohidrato_3_cant')->unsigned()->nullable()->after('carbohidrato_3');
            $table->integer('jugo_cant')->unsigned()->nullable()->after('jugo');
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
            $table->dropColumn('sopa_cant');
            $table->dropColumn('ensalada_cant');
            $table->dropColumn('ejecutivo_cant');
            $table->dropColumn('dieta_cant');
            $table->dropColumn('vegetariano_cant');
            $table->dropColumn('carbohidrato_1_cant');
            $table->dropColumn('carbohidrato_2_cant');
            $table->dropColumn('carbohidrato_3_cant');
            $table->dropColumn('jugo_cant');
        });
    }
}
