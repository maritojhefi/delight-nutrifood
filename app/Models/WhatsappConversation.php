<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para la tabla `whatsapp_conversations`.
 * Almacena los mensajes individuales vinculados a una sesión.
 *
 * @property int $id
 * @property int $whatsapp_session_id
 * @property int|null $user_id
 * @property bool $es_agente
 * @property string|null $tipo
 * @property array|null $contenido
 * @property bool $archivado
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class WhatsappConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        "whatsapp_session_id",
        "user_id",
        "es_agente",
        "tipo",
        "contenido",
        "archivado"
    ];

    protected $casts = [
        "archivado" => "boolean",
        "es_agente" => "boolean",
        "contenido" => "array",
    ];

    /**
     * Define la relación: Un mensaje pertenece a una Sesión de Whatsapp.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(WhatsappSession::class, 'whatsapp_session_id');
    }

    /**
     * Define la relación: Un mensaje puede estar vinculado a un Usuario (opcional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para obtener solo los mensajes de la conversación ACTIVA (no archivados).
     */
    public function scopeActivos(Builder $query)
    {
        return $query->where('archivado', false);
    }

    /**
     * Scope para obtener solo los mensajes archivados.
     */
    public function scopeArchivados(Builder $query)
    {
        return $query->where('archivado', true);
    }
}
