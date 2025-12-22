<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo para la tabla `whatsapp_sessions`.
 * Almacena el contexto (memoria) actual del agente para un número de teléfono (usuario o lead).
 *
 * @property int $id
 * @property string $telefono
 * @property int|null $user_id
 * @property array|null $metadata
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class WhatsappSession extends Model
{
    use HasFactory;

    protected $fillable = [
        "telefono",
        "user_id",
        "metadata"
    ];

    protected $casts = [
        "metadata" => "array",
    ];

    /**
     * Define la relación: Una sesión puede pertenecer a un usuario registrado (opcional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación: Una sesión tiene muchas conversaciones (mensajes).
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(WhatsappConversation::class);
    }

    /**
     * Genera la estructura JSON inicial para el campo metadata.
     */
    public static function generarJsonInicial(): array
    {
        return [
            "nombre_usuario" => null,
        ];
    }

    // Método de ayuda para acceder al nombre del usuario desde metadata
    public function getNombreUsuarioAttribute(): ?string
    {
        return $this->metadata['nombre_usuario'] ?? null;
    }
}
