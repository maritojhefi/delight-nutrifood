<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappPlanAlmuerzosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_plan_almuerzos', function (Blueprint $table) {
            $table->id();
            $table->string('cliente_id');
            $table->integer('cantidad')->default(1);
            $table->boolean('paso_segundo')->default(false);
            $table->boolean('paso_carbohidrato')->default(false);
            $table->boolean('paso_metodo_envio')->default(false);
            $table->boolean('paso_metodo_empaque')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_plan_almuerzos');
    }
}
