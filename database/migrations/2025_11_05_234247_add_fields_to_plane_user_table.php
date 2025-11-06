<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPlaneUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plane_user', function (Blueprint $table) {
            $table->dateTime('sopa_despachada_at')->nullable();
            $table->dateTime('segundo_despachado_at')->nullable();
            $table->dateTime('despachado_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plane_user', function (Blueprint $table) {
            $table->dropColumn('sopa_despachada_at');
            $table->dropColumn('segundo_despachado_at');
            $table->dropColumn('despachado_at');
        });
    }
}
