<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('tag_id')->references('id')->on('tags');
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
        Schema::dropIfExists('producto_tag');
    }
}
