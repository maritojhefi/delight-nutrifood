<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrupoAdicionalesIdToAdicionaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adicionales', function (Blueprint $table) {
            $table->foreignId('grupo_adicionales_id')->nullable()->constrained('grupo_adicionales');
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
            $table->dropForeign(['grupo_adicionales_id']);
            $table->dropColumn('grupo_adicionales_id');
        });
    }
}
