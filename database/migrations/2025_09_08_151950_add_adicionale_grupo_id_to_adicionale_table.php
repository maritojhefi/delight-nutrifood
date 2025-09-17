<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdicionaleGrupoIdToAdicionaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adicionales', function (Blueprint $table) {
            $table->foreignId('adicionale_grupo_id')->nullable()->constrained('adicionale_grupo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adicionales', function (Blueprint $table) {
            $table->dropForeign(['adicionale_grupo_id']);
            $table->dropColumn('adicionale_grupo_id');
        });
    }
}
