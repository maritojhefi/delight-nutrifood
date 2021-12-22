<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaneUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plane_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plane_id')->nullable();
            $table->foreign('plane_id')->references('id')->on('planes');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            //$table->integer('restante')->unsigned()->nullable();
            //$table->date('dia_limite')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->string('color')->nullable()->default('#20C995');
            $table->string('title')->nullable();
            $table->string('detalle',500)->nullable();
            $table->string('estado', 100)->nullable()->default('pendiente');
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
        Schema::dropIfExists('plane_user');
    }
}
