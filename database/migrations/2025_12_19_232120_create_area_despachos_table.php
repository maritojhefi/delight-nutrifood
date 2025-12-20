<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaDespachosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas_despachos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_area');
            $table->string('codigo_area');
            $table->string('descripcion')->nullable();
            $table->string('id_impresora')->nullable();
            $table->boolean('activo')->default(true);
            $table->foreignId('sucursale_id')->constrained('sucursales');
            $table->timestamps();
        });
        Artisan::call('db:seed', [
            '--class' => 'AreaDespachoSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas_despachos');
    }
}
