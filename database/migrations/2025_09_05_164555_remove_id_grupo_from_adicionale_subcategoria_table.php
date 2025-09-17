<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIdGrupoFromAdicionaleSubcategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adicionale_subcategoria', function (Blueprint $table) {
            $table->dropColumn('id_grupo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adicionale_subcategoria', function (Blueprint $table) {
            $table->unsignedBigInteger('id_grupo');
        });
    }
}
