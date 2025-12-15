<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                    ->unique() // Crucial: Una sola sesión activa por usuario
                    ->constrained('users') // Vinculado a la tabla de usuarios existente
                    ->cascadeOnDelete();

            $table->json('metadata')->nullable(); // Almacena el contexto de la conversación activa

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
        Schema::dropIfExists('whatsapp_sessions');
    }
}
