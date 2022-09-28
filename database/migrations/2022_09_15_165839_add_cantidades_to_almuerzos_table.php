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
            $table->integer('sopa_cant')->unsigned()->nullable()->after('sopa')->default(0);
            $table->integer('ensalada_cant')->unsigned()->nullable()->after('ensalada')->default(0);
            $table->integer('ejecutivo_cant')->unsigned()->nullable()->after('ejecutivo')->default(0);
            $table->integer('dieta_cant')->unsigned()->nullable()->after('dieta')->default(0);
            $table->integer('vegetariano_cant')->unsigned()->nullable()->after('vegetariano')->default(0);
            $table->integer('carbohidrato_1_cant')->unsigned()->nullable()->after('carbohidrato_1')->default(0);
            $table->integer('carbohidrato_2_cant')->unsigned()->nullable()->after('carbohidrato_2')->default(0);
            $table->integer('carbohidrato_3_cant')->unsigned()->nullable()->after('carbohidrato_3')->default(0);
            $table->integer('jugo_cant')->unsigned()->nullable()->after('jugo')->default(0);
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
