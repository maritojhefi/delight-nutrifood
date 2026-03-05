<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('whatsapp_conversations', function (Blueprint $table) {
            $table->id();

            // Vinculado a la sesión
            $table->foreignId('whatsapp_session_id')
                    ->constrained('whatsapp_sessions')
                    ->cascadeOnDelete();

            // id de usuario anulable
            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

            $table->boolean('es_agente')->default(false); // Indica si el mensaje es de un agente
            $table->string('tipo')->nullable(); // e.g., 'text', 'location', 'image'
            $table->longText('contenido')->nullable(); // Contenido del mensaje
            $table->boolean('archivado')->default(false); // Control de mensajes archivados

            $table->timestamps();

            // Índices optimizados para consultas
            $table->index(['whatsapp_session_id', 'archivado', 'created_at'], 'wapp_conv_fast_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_conversations');
    }
}
