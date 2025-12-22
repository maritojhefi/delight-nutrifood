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

            // Número de teléfono
            $table->string('telefono')->unique()->index();

            // User ID anulable permitiendo asociar con usuarios registrados o invitados
            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete(); // De eliminarse el usuario, se mantiene una sesion "desvinculada" o histórica.

            // 3. Contexto de la IA (Estado del flujo, variables temporales, etc.)
            $table->json('metadata')->nullable();

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
