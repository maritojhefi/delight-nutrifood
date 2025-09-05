<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdicionaleSubcategoriaRedefineIdGrupo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adicionale_subcategoria', function (Blueprint $table)
        {
            $table->dropColumn('id_grupo');
        });

        Schema::table('adicionale_subcategoria', function (Blueprint $table)
        {
            $table->unsignedBigInteger('grupo_adicionales_id')->nullable();
            $table->foreign('grupo_adicionales_id')->references('id')->on('grupo_adicionales')->onDelete('set null');
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
            $table->dropForeign(['id_grupo']);
            $table->dropColumn('id_grupo');
        });
    }
}
