<?php

namespace App\Helpers;

use App\Models\WhatsappConversation;
use App\Models\WhatsappSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Helpers\GlobalHelper;
use App\Helpers\WhatsappNotification;
use App\Models\Horario;
use App\Models\User;
use Exception;

class CircuitoIA
{
    /**
    * @var WhatsappSession|null Sesión actual del teléfono
    */
    public ?WhatsappSession $sesionActual = null;

    /**
    * @var string|null ID del usuario
    */
    public ?int $idUsuario = null;

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

    /**
    * @var bool Verdadero si el mensaje es de un usuario, falso si es de la API
    */
    public bool $desdeUsuario = false;

    /**
    * @var string|null El identificador de la conversación de WhatsApp
    */
    public ?string $idConversacion = null;

    /**
    * @var string|null El tema de la conversación
    */
    public ?string $temaConversacion = null;

    public function __construct()
    {
        // Constructor logic here
    }

    const TEMA_SALUDO = "saludo";
    const TEMA_PRESENTACION = "presentacion";
    const TEMA_ABOUT_US = "about_us";
    const TEMA_SOPORTE = "soporte";
    const TEMA_HORARIOS = "horarios";
    const TEMA_PRODUCTOS_CATEGORIA = "productos_categoria";
    const TEMA_PRODUCTO_ESPECIFICO = "producto_especifico";
    const TEMA_DEBUG = "debug";

    const TEMAS_CONVERSACION = [
        self::TEMA_SALUDO,
        self::TEMA_PRESENTACION,
        self::TEMA_ABOUT_US,
        self::TEMA_SOPORTE,
        self::TEMA_HORARIOS,
        self::TEMA_PRODUCTOS_CATEGORIA,
        self::TEMA_PRODUCTO_ESPECIFICO,
        self::TEMA_DEBUG
    ];

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
                    self::enviarRespuestaWhatsapp($contenido);
                }
            } catch (\Throwable $th) {
                Log::error("CircuitoIAV2 - EXCEPCION en circuitoIAWhatsapp", [
                    "mensaje" => $th->getMessage(),
                    "linea" => $th->getLine(),
                    "archivo" => $th->getFile(),
                    "trace" => $th->getTraceAsString(),
                ]);
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
        if (!isset($json->message->from)) {
            return false;
        }
        $this->numeroOrigen = $json->message->from;
        $this->cuerpo = $json->message->content;
        $this->tipo = $json->message->type;
        $this->desdeUsuario = true;
        $this->idConversacion = $json->conversation->id;
        $this->estadoMensaje = $json->message->status;

        Log::debug("Mensaje recibido de: " . $this->numeroOrigen);

        // Recuperar la sesión asignada al teléfono
        $sesion = WhatsappSession::where('telefono', $this->numeroOrigen)->first();
        if (!$sesion) {
            // De no existir la sesión - verificar si existe un usuario vinculado al número telefónico
            $usuario = User::whereRaw("CONCAT(codigo_pais, telf) = ?", [$this->numeroOrigen])->first();
            // Crear la sesión (Vinculada si se encuentra un usuario, anónima si no)
            $sesion = WhatsappSession::create([
                'telefono' => $this->numeroOrigen,
                'user_id' => $usuario ? $usuario->id : null,
                'metadata' => WhatsappSession::generarJsonInicial(),
            ]);
        }

        $this->sesionActual = $sesion;
        $this->idUsuario = $sesion->user_id; // Será null si es anónimo

        return true;
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
        $contenidoAGuardar = [];

        // Normalización según el tipo de mensaje entrante
        if ($this->tipo == "audio") {
            $textoTranscrito = self::interpretarAudioATexto();

            $this->tipo = "text";

            $contenidoAGuardar = [
                "text" => $textoTranscrito,
                "meta" => "transcribed_audio"
            ];
        } elseif ($this->tipo == "text") {
            // Si $this->cuerpo es un objeto/string, normalizar a array.
            $texto = is_string($this->cuerpo) ? $this->cuerpo : ($this->cuerpo->body ?? $this->cuerpo->text ?? '');

            $contenidoAGuardar = [
                "text" => $texto
            ];
        } elseif ($this->tipo == "location") {
            // Estructura estándar para ubicación
            $contenidoAGuardar = [
                "location" => [
                    "latitude" => $this->cuerpo->location->latitude ?? null,
                    "longitude" => $this->cuerpo->location->longitude ?? null,
                    "address" => $this->cuerpo->location->address ?? null, // Si WhatsApp lo envía
                ]
            ];
        } else {
            // Fallback para otros tipos (imagen, documento, etc)
            $contenidoAGuardar = (array) $this->cuerpo;
        }

        // Guardado en la tabla whatsapp_conversations
        WhatsappConversation::create([
            "whatsapp_session_id" => $this->sesionActual->id,
            "user_id" => $this->idUsuario,
            "tipo" => $this->tipo,
            "es_agente" => false,
            "contenido" => $contenidoAGuardar,
        ]);
    }

    private function procesarMedianteAgenteIA()
    {
        $ultimoMensaje = WhatsappConversation::where(
            "user_id",
            $this->idUsuario,
        )
            ->where("archivado", false)
            ->orderByDesc("id")
            ->first();

        $contenidoUltimoMensaje = $ultimoMensaje->contenido;

        // Entregar productos del contexto de ser necesario
        $informaciónProductosContexto = [];

        $respuestaSolicitud = $this->procesarSolicitudCliente($contenidoUltimoMensaje['text'],null);
        // Flujo cuidado de la conversación

        $promptsSolicitudPrincipal = $this->construirPrompts($respuestaSolicitud);

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
                "messages" => $promptsSolicitudPrincipal,
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

    private function construirPrompts($infoSolicitud): array
    {
        $temaConversacion = $infoSolicitud['tema'];

        Log::debug("Información de la solicitud para construir prompts", [
            $infoSolicitud,
        ]);

        $promptsIdentidadAgente = [
            [
                "role" => "system",
                "content" => "Eres un Agente IA para." . GlobalHelper::getValorAtributoSetting('nombre_sistema')  . ",
                Utiliza la personalidad de un ayudante de tienda amable y cordial."
            ]
        ];

        $promptsSegunTema = [];

        switch ($temaConversacion) {
            case self::TEMA_DEBUG:
                $promptsSegunTema[] =
                [
                    "role" => "user",
                    "content" => "EL TEMA DE LA CONVERSACION ES DEBUG, INDEPENDIENTEMENTE DE LA SOLICITUD y DE MI HISTORIAL, TU PRIORIDAD MAXIMA ES RESPONDER 'QUEEEEESOOOOOOOOO' "
                ];
                break;
            case self::TEMA_HORARIOS:
                $horarios = Horario::all();

                $promptsSegunTema[] =
                [
                    "role" => "user",
                    "content" => "El tema de la solicitud principal es HORARIOS, esta es la informacióñ de los horarios de atención: $horarios,
                    responde de manera natural, si hay horarios continuos, menciónalos como un único rango"
                ];
                break;
            default:
                break;
        }

        $promptInstruccionHistorial = [
            [
                "role" => "system",
                "content" => "Recibirás el historial de la conversación mediante inputs del rol user, solo ten en cuenta los últimos mensajes para entender lo solicitado
                Si ya has ofrecido una respuesta válida aceptada en el historial, puedes pasar de ella y enfocarte en la última solicitud."
            ]
        ];

        $historialConversacion = $this->obtenerConversacionDB();

        $promptsSolicitudPrincipal = array_merge(
            $promptsIdentidadAgente,        // 1. Identidad del agente
            $promptsSegunTema,              // 2. Instrucciones específicas del tema (DEBUG, etc.)
            $promptInstruccionHistorial,    // 3. Cómo manejar el historial
            $historialConversacion          // 4. Historial real de mensajes
        );

        Log::debug("PromptsSolicitudPrincial", [
            $promptsSolicitudPrincipal
        ]);

        return $promptsSolicitudPrincipal;
    }

    private function obtenerConversacionDB(): array
    {
        $mensajes = WhatsappConversation::activos()
                    ->where('user_id', $this->idUsuario)->get();

        $totalMensajes = $mensajes->count();
        $indiceActual = 0;

        $promptsHistorialConversacion = [];

        foreach($mensajes as $mensaje) {
            $indiceActual++;
            $esUltimoMensaje = $indiceActual === $totalMensajes;
            $contenido = $mensaje->contenido;

            $rol = $mensaje->es_agente ? "assistant" : "user";

            switch ($mensaje->tipo)
            {
                case "text":
                    // FLujo texto recibido
                    if (!empty($contenido["text"]))
                    {
                        $promptsHistorialConversacion[] = [
                            "role" => $rol,
                            "content" => $esUltimoMensaje ? "Historial:" . $contenido["text"]
                            : $contenido["text"],
                        ];
                    }
                    break;
                case "location":
                    // Flujo ubicación recibida
                    if (isset($contenido['location'])) {
                        $lat = $contenido['location']['latitude'];
                        $lon = $contenido['location']['longitude'];
                        $promptsHistorialConversacion[] = [
                            "role" => $rol,
                            "content" => "El usuario envió su ubicación: Latitud {$lat}, Longitud {$lon}."
                        ];
                    }
                    break;
                case "image":
                    break;
                default:
                    break;
            };
        }

        return $promptsHistorialConversacion;
    }

    private function procesarSolicitudCliente(
        string $mensaje,
        ?array $productosContexto,
    ) {
        $promptSolicitudCliente = [
            [
                "role" => "system",
                "content" =>
                    '
                    Tu tarea es determinar el tema de la solicitud del cliente, el cual puede pertenecer a uno de los siguientes : [' .
                    implode(", ", self::TEMAS_CONVERSACION) .
                    '].
                    La decisión del tema puede ser influenciada si el mensaje está acompañado de información de productos, pero no es definitivo.
                    En el caso de que el tema coincida con los siguientes: [' .
                    self::TEMA_PRODUCTOS_CATEGORIA .
                    ", " .
                    self::TEMA_PRODUCTO_ESPECIFICO .
                    ']
                    debes realizar tambien un análisis de la categoría o negocio específico solicitado para el cliente, para extraer la información de búsqueda de la siguiente manera:
                    > Instrucciones:
                    - texto_busqueda: La palabra o frase clave para buscar, sin palabras extras como "negocios de", "busco", "quiero", etc.
                    - sinonimos: Lista de 3-5 términos relacionados que ayuden a encontrar el mismo tipo de servicio/negocio.

                    Responde ÚNICAMENTE con un JSON válido sin markdown ni explicaciones.
                    Formato esperado:
                    {
                        "tema": "tema_detectado",
                        "texto_busqueda": "término principal limpio",
                        "sinonimos": ["sinónimo1", "sinónimo2", "sinónimo3"]
                    }
                    Manten las dos ultimas propiedades vacías cuando el tema no sea [' .
                    self::TEMA_PRODUCTOS_CATEGORIA .
                    " o " .
                    self::TEMA_PRODUCTO_ESPECIFICO .
                    '].
                ',
            ],
        ];

        // if (!empty($productosContexto))
        // {
        //     $promptSolicitudCliente[] = [
        //         "role" => "user",
        //         "content" =>
        //             "Información de productos en el contexto: [" . json_encode($productosContexto) . "]",
        //     ];
        // }

        $conversacionDB = self::obtenerConversacionDB();
        $promptSolicitudCliente = array_merge(
            $promptSolicitudCliente,
            $conversacionDB,
        );

        // Consulta para obtener el tema y el término de búsqueda
        $consultaTema = Http::withHeaders([
            "Authorization" =>
                "Bearer " . config("macrobyte.deepseek_ia.api_key"),
        ])->post(
            config("macrobyte.deepseek_ia.base_url") .
                config("macrobyte.deepseek_ia.chat_endpoint"),
            [
                "model" => config("macrobyte.deepseek_ia.model"),
                "temperature" => 0.3,
                "messages" => $promptSolicitudCliente,
                "max_tokens" => 200,
                "response_format" => ["type" => "json_object"], // Esperamos respuesta en formato JSON
            ],
        );

        $respuestaTema = json_decode($consultaTema->body());

        if (isset($respuestaTema->choices[0]->message->content)) {
            $contenido = $respuestaTema->choices[0]->message->content;

            // Limpiar posibles marcadores de markdown
            $contenido = preg_replace("/```json\s*|\s*```/", "", $contenido);
            $contenido = trim($contenido);

            try {
                $resultado = json_decode($contenido, true);

                if (
                    !isset($resultado["texto_busqueda"]) ||
                    !isset($resultado["sinonimos"])
                ) {
                    throw new Exception("JSON incompleto");
                }

                Log::debug("procesarSolicitudCliente:", [
                    "Mensaje" => $mensaje,
                    "Productos Contexto" => is_array($productosContexto)
                        ? count($productosContexto)
                        : 0,
                    "texto_busqueda" => $resultado["texto_busqueda"],
                    "sinonimos" => $resultado["sinonimos"],
                ]);

                return $resultado;
            } catch (Exception $e) {
                // Fallback: usar el contenido como texto simple
                Log::warning("Error parseando JSON de IA, usando fallback", [
                    "error" => $e->getMessage(),
                    "contenido" => $contenido,
                ]);

                return [
                    "texto_busqueda" => $contenido,
                    "sinonimos" => [],
                ];
            }
        } else {
            // MacrobyteLog::create([
            //     "titulo" => "Error de respuesta deepseek",
            //     "estado" => "error",
            //     "log" => $consultaTema->body(),
            // ]);
            Log::error("Error de respuesta de DeepSeek Log", [
                "error" => $consultaTema->body(),
            ]);
            throw new Exception("Respuesta errónea de DeepSeek");
        }
    }

    private function enviarRespuestaWhatsapp(string $contenido): void
    {
        // Patrones soportados por el agente
        $patronImagen = "/#imagen\s+url=(https:\/\/\S+)\s+texto=(.+)/";
        $patronUbicacion = "/#ubicacion\s+latitud=([-+]?\d+\.\d+)\s+longitud=([-+]?\d+\.\d+)\s+texto=(.+)/";

        // IMAGEN
        if (preg_match($patronImagen, $contenido, $matches)) {

            $urlImagen = $matches[1];
            $texto = $matches[2];

            // Enviar imagen (con manejo de error para ambiente de prueba)
            $this->enviarMensajeWhatsapp("image", [$urlImagen]);

            // Guardar imagen
            WhatsappConversation::create([
                "whatsapp_session_id" => $this->sesionActual->id,
                "user_id" => $this->idUsuario,
                "es_agente" => true,
                "tipo" => "image",
                "contenido" => [
                    "image" => [
                        "url" => $urlImagen,
                    ]
                ],
            ]);

            // Enviar texto descriptivo
            $this->enviarMensajeWhatsapp("text", [$texto]);

            // Guardar texto
            WhatsappConversation::create([
                "whatsapp_session_id" => $this->sesionActual->id,
                "user_id" => $this->idUsuario,
                "es_agente" => true,
                "tipo" => "text",
                "contenido" => [
                    "text" => $texto
                ],
            ]);

            return;
        }

        // UBICACIÓN
        if (preg_match($patronUbicacion, $contenido, $matches)) {

            $lat = (float) $matches[1];
            $lon = (float) $matches[2];
            $texto = $matches[3];

            $this->enviarMensajeWhatsapp("location", [$lat, $lon]);

            WhatsappConversation::create([
                "whatsapp_session_id" => $this->sesionActual->id,
                "user_id" => $this->idUsuario,
                "es_agente" => true,
                "tipo" => "location",
                "contenido" => [
                    "location" => [
                        "latitude" => $lat,
                        "longitude" => $lon,
                    ]
                ],
            ]);

            $this->enviarMensajeWhatsapp("text", [$texto]);

            WhatsappConversation::create([
                "whatsapp_session_id" => $this->sesionActual->id,
                "user_id" => $this->idUsuario,
                "es_agente" => true,
                "tipo" => "text",
                "contenido" => [
                    "text" => $texto
                ],
            ]);

            return;
        }

        // TEXTO NORMAL (fallback)
        $this->enviarMensajeWhatsapp("text", [$contenido]);

        WhatsappConversation::create([
            "whatsapp_session_id" => $this->sesionActual->id,
            "user_id" => $this->idUsuario,
            "es_agente" => true,
            "tipo" => "text",
            "contenido" => [
                "text" => $contenido
            ],
        ]);
    }

    /**
    * Envía un mensaje a WhatsApp con manejo de errores para ambiente de prueba.
    *
    * @param string $tipo Tipo de contenido (text, image, location, etc.)
    * @param array $contenido Contenido del mensaje
    * @return bool True si se envió correctamente, false si hubo error
    */
    private function enviarMensajeWhatsapp(string $tipo, array $contenido): bool
    {
        try {
            WhatsappNotification::conversation()
                ->withId($this->idConversacion)
                ->typeContent($tipo)
                ->content($contenido)
                ->send();

            Log::debug("Mensaje WhatsApp enviado correctamente", [
                "tipo" => $tipo,
                "conversacion_id" => $this->idConversacion,
            ]);

            return true;
        } catch (\Throwable $th) {
            Log::warning("No se pudo enviar mensaje WhatsApp (ambiente de prueba o conversación expirada)", [
                "tipo" => $tipo,
                "conversacion_id" => $this->idConversacion,
                "error" => $th->getMessage(),
            ]);

            return false;
        }
    }

}
