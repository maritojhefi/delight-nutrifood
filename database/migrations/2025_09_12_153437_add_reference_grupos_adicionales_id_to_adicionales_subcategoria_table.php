<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceGruposAdicionalesIdToAdicionalesSubcategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adicionale_subcategoria', function (Blueprint $table) {
            $table->unsignedBigInteger('grupo_adicionales_id')->after('subcategoria_id')->nullable();
            $table->foreign('grupo_adicionales_id')->references('id')->on('grupos_adicionales');
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
            $table->dropForeign(['grupo_adicionales_id']);
            $table->dropColumn('grupo_adicionales_id');
        });
    }
}
