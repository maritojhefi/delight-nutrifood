<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para la tabla `whatsapp_sessions`.
 * Almacena el contexto (memoria) actual del agente para un usuario.
 *
 * @property int $id
 * @property int $user_id
 * @property array|null $metadata
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class WhatsappSession extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "metadata"];

    protected $casts = [
        "metadata" => "array",
    ];

    /**
     * Define la relación: Una sesión pertenece a un solo Usuario.
     */
    public function user(): BelongsTo
    {
        // Asume que tu modelo User está en App\Models\User
        return $this->belongsTo(User::class);
    }

    /**
     * Genera la estructura JSON inicial para el campo metadata.
     */
    public static function generarJsonInicial(): array
    {
        return [
            "nombre_usuario" => null,
            // Aquí puedes añadir más variables de estado que el agente necesite
        ];
    }

    // Método de ayuda para acceder al nombre del usuario desde metadata
    public function getNombreUsuarioAttribute(): ?string
    {
        return $this->metadata['nombre_usuario'] ?? null;
    }
}
