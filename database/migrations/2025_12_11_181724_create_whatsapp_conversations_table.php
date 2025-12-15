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

            $table->foreignId('user_id')
                    ->constrained('users') // Vinculado a la tabla de usuarios existente
                    ->cascadeOnDelete();

            $table->boolean('es_agente')->default(false); // Indica si el mensaje es de un agente

            $table->string('tipo')->nullable(); // e.g., 'text', 'location', 'image'
            $table->longText('contenido')->nullable(); // Contenido del mensaje
            $table->boolean('archivado')
                    ->default(false); // Control de mensajes archivados

            $table->timestamps();

            // Optimize for AI context retrieval and 10-minute check
            $table->index(['user_id', 'archivado', 'created_at']);
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
