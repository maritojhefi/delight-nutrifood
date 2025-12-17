<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconoLucideToHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
         Schema::table('horarios', function (Blueprint $table) {
             $table->string('icono_lucide', 50)
                ->nullable()
                ->after('posicion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down(): void
     {
         Schema::table('horarios', function (Blueprint $table) {
             $table->dropColumn('icono_lucide');
         });
     }
}
