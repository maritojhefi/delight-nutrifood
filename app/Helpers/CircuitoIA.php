<?php

namespace App\Helpers;

use App\Models\WhatsappConversation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Helpers\GlobalHelper;
use App\Helpers\WhatsappNotification;

class CircuitoIA
{
    /**
    * @var string|null ID del usuario
    */
    public ?int $idUsuario = null;

    /**
    * @var bool Verdadero si el mensaje es de un usuario, falso si es de la API
    */
    public bool $desdeUsuario = false;

    /**
    * @var string|null El número de teléfono que envía el mensaje
    */
    public ?string $numeroOrigen = null;

    /**
    * @var string|null Estado del mensaje (pendiente, recibido, etc.)
    */
    public ?string $estadoMensaje = null;

    /**
    * @var string|null Tipo de mensaje (texto, imagen, audio, etc.)
    */
    public ?string $tipo = null;

    /**
    * @var object|null El contenido del mensaje
    */
    public ?object $cuerpo = null;

    public function __construct()
    {
        // Constructor logic here
    }

    public function circuitoIAWhatsapp($json): void
    {
        $esOrigenCliente = $this->procesarInformacion($json);
        // Implementation logic here
        if ($esOrigenCliente) {
            $this->leerYGuardarMensajeProcedencia();
            try {
                $contenido = $this->procesarMedianteAgenteIA();
                if ($contenido)
                {
                    // self::enviarRespuestaWhatsapp($contenido);
                }
            } catch (\Throwable $th) {
                Log::error("CircuitoIAV2 - EXCEPCION en circuitoIAWhatsapp", [
                    "mensaje" => $th->getMessage(),
                    "linea" => $th->getLine(),
                    "archivo" => $th->getFile(),
                    "trace" => $th->getTraceAsString(),
                ]);

                // MacrobyteLog::create([
                //     "titulo" => "Log en circuito IA",
                //     "estado" => "error",
                //     "linea" => $th->getLine(),
                //     "log" => $th->getMessage(),
                // ]);
            }
        }
    }

    /**
    * Hidratar las propiedades de la clase desde el JSON recibido.
    * * @param object $json The decoded JSON object
    * @return bool Returns true if valid source (from user), false otherwise.
    */
    public function procesarInformacion($json): bool
    {
        if (isset($json->message->from)) {
            $this->cuerpo = $json->message->content;
            $this->tipo = $json->message->type;
            $this->idUsuario = 675; // Hardcodeado para tests
            $this->desdeUsuario = $json->message->from ? true : false;
            $this->numeroOrigen = $json->message->from;
            Log::debug("Mensaje recibido desde NÚMERO TELEFÓNICO: " . $this->numeroOrigen);
            $this->estadoMensaje = $json->message->status;
            return true;
        } else {
            return false;
        }
    }

    /**
    * Transformar mensajes de audio a texto con almacenamiento en disco.
    * @return string Devuelve el texto transcribido del audio.
    */
    public function interpretarAudioATexto(): string
    {
        // Descargar el audio de WhatsApp
        $audioContent = Http::get($this->cuerpo->audio->url)->body();

        // Generar nombre único para el archivo
        $fileName = "audio_whatsapp_" . time() . ".m4a";

        // Guardar el archivo en el disco public_images
        Storage::disk("public_images")->put($fileName, $audioContent);

        // Construir la ruta completa usando public_path
        // public_images está configurado en: public_path('imagenes')
        $filePath = public_path("imagenes/" . $fileName);

        try {
            // Enviar el archivo a OpenAI para transcripción
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . config("macrobyte.open_ai.api_key"),
                "OpenAI-Organization" => "org-t7AAFmYGwVQwLmstMyTDltXX",
            ])
            ->attach("file", file_get_contents($filePath), $fileName)
            ->post("https://api.openai.com/v1/audio/transcriptions", [
                "model" => "whisper-1",
            ]);

            // Verificar que la petición fue exitosa
            if (!$response->successful()) {
                throw new \Exception("Error en la transcripción: " . $response->body());
            }

            // Decodificar y retornar el texto transcrito
            $resultado = json_decode($response->body());
            return $resultado->text ?? '';

        } finally {
            // Limpiar el archivo temporal
            if (Storage::disk("public_images")->exists($fileName)) {
                Storage::disk("public_images")->delete($fileName);
            }
        }
    }


    private function leerYGuardarMensajeProcedencia(): void
    {
        if ($this->tipo != "audio") {
            // Almacenar el mensaje del usuario
            WhatsappConversation::create([
                "user_id" => $this->idUsuario,
                "tipo" => $this->tipo,
                "contenido" => $this->cuerpo,
            ]);
        } else {
            // Analizar el audio y almacenar el mensaje del usuario
            $textoTranscrito = self::interpretarAudioATexto();
            WhatsappConversation::create([
                "user_id" => $this->idUsuario,
                "tipo" => "text",
                "contenido" => json_encode(
                    WhatsappNotification::conversation()
                        ->typeContent("text")
                        ->content([$textoTranscrito])
                        ->returnContent()["content"],
                ),
            ]);
        }
    }

    private function procesarMedianteAgenteIA()
    {
        // Flujo cuidado de la conversación

        // Flujo de prueba
        //  Definir un prompt base de saludo
        $promptsBasePrueba = [
            [
                "role" => "system",
                "content" => "Eres un Agente IA para." . GlobalHelper::getValorAtributoSetting('nombre_sistema')  . ",
                asegúrate de presentarte al usuario con un saludo cálido en su primera interacción."
            ]
        ];

        $historialConversacion = $this->obtenerConversacionDB();
        $promptsBasePrueba = array_merge($promptsBasePrueba, $historialConversacion);

        //  Preparar a la IA con el prompt y enviar el mensaje
        $respuestaIA = Http::withHeaders([
            "Authorization" =>
                "Bearer " . config("macrobyte.deepseek_ia.api_key"),
        ])->post(
            config("macrobyte.deepseek_ia.base_url") .
                config("macrobyte.deepseek_ia.chat_endpoint"),
            [
                "model" => config("macrobyte.deepseek_ia.model"),
                "temperature" => 0.5,
                "messages" => $promptsBasePrueba,
                "max_tokens" => 300,
                // "response_format" => ["type" => "json_object"],
            ],
        );

        if ($respuestaIA->successful()) {
            $responseData = $respuestaIA->json();
            $usage = $responseData["usage"];
            $promptTokens = $usage["prompt_tokens"] ?? 0;
            $completionTokens = $usage["completion_tokens"] ?? 0;
            $totalTokens = $usage["total_tokens"] ?? 0;
            // Log de consumo de detalles
            Log::debug("Uso de tokens en la API de la IA", [
                "total_tokens" => $totalTokens,
                "prompt_tokens" => $promptTokens,
                "completion_tokens" => $completionTokens,
            ]);

            Log::debug("Respuesta completa de la IA", [
                "status" => $respuestaIA->status(),
                "body" => $respuestaIA->body(),
            ]);

            return $responseData["choices"][0]["message"]["content"];
        } else {
            Log::error("Error en la respuesta de la IA", [
                "status" => $respuestaIA->status(),
                "body" => $respuestaIA->body(),
            ]);
            return null;
        }
    }

    private function obtenerConversacionDB(): array
    {
        $mensajes = WhatsappConversation::activos()->where('user_id', $this->idUsuario)->get();
        $totalMensajes = $mensajes->count();
        $indiceActual = 0;

        $promptsHistorialConversacion = [
            [
                "role" => "user",
                "content" => "Recibirás el historial de la conversación mediante inputs del rol user, solo ten en cuenta los últimos mensajes para entender lo solicitado
                Si ya has ofrecido una respuesta válida aceptada en el historial, puedes pasar de ella y enfocarte en la última solicitud."
            ]
        ];

        foreach($mensajes as $mensaje) {
            $indiceActual++;
            $esUltimoMensaje = $indiceActual === $totalMensajes;
            $contenido = $mensaje->contenido;

            if ($mensaje->es_agente)
            {
                $rol = "assistant";
            } else {
                $rol = "user";
            };
            switch ($mensaje->tipo)
            {
                case "text":
                    if ($esUltimoMensaje)
                    {
                        // Prompts para mensajes del Historial
                        $promptMensaje = [
                            "role" => $rol,
                            "content" => "Historial: " . $contenido["text"] . "\n",
                        ];
                    } else {
                        // Prompt del último mensaje (mensaje actual)
                        $promptMensaje = [
                            "role" => $rol,
                            "content" => $contenido["text"] . "\n",
                        ];
                    }
                    array_push($promptsHistorialConversacion, $promptMensaje);
                    break;
                case "image":
                    // Flujo imagen recibida
                    break;
                case "location":
                    // Flujo ubicación recibida
                    $promptMensajeUbicación = [
                        "role" => $rol,
                        "content" => "Ubicación: " . $contenido["location"]["latitude"] . ", " . $contenido["location"]["longitude"] . "\n",
                    ];
                    array_push($promptsHistorialConversacion, $promptMensajeUbicación);
                    break;
                default:
                    break;
            };
        }

        return array_values($promptsHistorialConversacion);
    }
}
