<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAdicionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adicionales', function (Blueprint $table) {
            $table->integer('cantidad')->unsigned()->default(0);
            $table->boolean('contable')->default(false);
            $table->string('codigo_cocina')->nullable();
        });
        Artisan::call('db:seed', [
            '--class' => 'CarbohidratosAdicionalesSeeder',
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
        Schema::table('adicionales', function (Blueprint $table) {
            $table->dropColumn('cantidad');
            $table->dropColumn('contable');
            $table->dropColumn('codigo_cocina');
        });
    }
}
