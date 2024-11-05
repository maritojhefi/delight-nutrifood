<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToAlmuerzosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('almuerzos', function (Blueprint $table) {
            $table->boolean('ejecutivo_tiene_carbo')->after('ejecutivo')->default(true);
            $table->boolean('vegetariano_tiene_carbo')->after('vegetariano')->default(true);
            $table->boolean('dieta_tiene_carbo')->after('dieta')->default(true);
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
            $table->dropColumn('ejecutivo_tiene_carbo');
            $table->dropColumn('vegetariano_tiene_carbo');
            $table->dropColumn('dieta_tiene_carbo');
        });
    }
}
