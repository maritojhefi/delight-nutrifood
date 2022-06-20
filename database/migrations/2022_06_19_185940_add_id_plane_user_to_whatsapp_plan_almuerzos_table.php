<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdPlaneUserToWhatsappPlanAlmuerzosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('whatsapp_plan_almuerzos', function (Blueprint $table) {
            $table->integer('id_plane_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('whatsapp_plan_almuerzos', function (Blueprint $table) {
            $table->dropColumn('id_plane_user');
        });
    }
}
