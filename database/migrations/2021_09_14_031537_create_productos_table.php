<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->nullable()->unique();
            $table->unsignedBigInteger('subcategoria_id')->nullable();
            $table->foreign('subcategoria_id')->references('id')->on('subcategorias');
            $table->decimal('precio')->nullable();
            $table->string('detalle')->nullable();
            $table->string('imagen')->nullable();
            $table->string('estado')->nullable()->default('activo');
            $table->string('codigoBarra')->nullable();
            $table->decimal('descuento')->nullable();
            $table->integer('puntos')->unsigned()->nullable()->default(0);
            $table->string('medicion')->default('unidad');
            $table->boolean('contable')->nullable()->default(false);
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
        Schema::dropIfExists('productos');
    }
}
