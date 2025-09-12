<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAdicionaleGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('adicionale_grupo');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('adicionale_grupo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('maximo_seleccionable')->unsigned();
            $table->boolean('es_obligatorio');
            $table->timestamps();
        });
    }
}
