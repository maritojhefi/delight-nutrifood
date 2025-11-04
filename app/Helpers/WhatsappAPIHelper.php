<?php

namespace App\Helpers;

use App\Models\Tarjeta;
use App\Models\NumeroWhatsapp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsappApiHelper
{
    private const BASE_URL = 'https://evo-api.mbyte.click';

    private $instance;
    private $apikey;
    private $receiver;
    private $remoteJid; // JID completo para operaciones especiales
    private $message;
    private $mediaUrl;
    private $mediaType; // image, video, document
    private $mimetype;
    private $caption;
    private $fileName;
    private $type; // texto, imagen, video, documento, ubicacion, plantilla
    private $template;
    private $variables = [];
    private $locationData = []; // name, address, latitude, longitude
    private $delay;
    private $linkPreview = false;

    /**
     * Establece las credenciales a partir del registro de NumeroWhatsapp.
     */
    public static function setNumero(string $idRegistro)
    {
        $registro = NumeroWhatsapp::find($idRegistro);

        if (!$registro) {
            throw new \Exception('Registro de WhatsApp no encontrado o configuración inválida.');
        }

        $helper = new self();
        $helper->instance = $registro->app_key; // appKey es la instancia
        $helper->apikey = $registro->auth_key;  // authKey es la apikey

        return $helper;
    }

    /**
     * Define el receptor del mensaje.
     * Acepta: '59175140175', '+59175140175', '59175140175@s.whatsapp.net'
     * Genera automáticamente el remoteJid para operaciones especiales.
     */
    public function para($receiver)
    {
        // Normalizar número: quitar el + si existe
        $receiver = ltrim($receiver, '+');

        // Detectar si ya tiene sufijo (@s.whatsapp.net, @c.us, @lid, @g.us)
        if (preg_match('/@(s\.whatsapp\.net|c\.us|lid|g\.us)$/', $receiver)) {
            // Ya tiene sufijo, extraer número limpio y preservar remoteJid completo
            $this->remoteJid = $receiver;
            $this->receiver = preg_replace('/@(s\.whatsapp\.net|c\.us|lid|g\.us)$/', '', $receiver);
        } else {
            // No tiene sufijo, es solo el número
            $this->receiver = $receiver;
            // Generar remoteJid por defecto con @s.whatsapp.net
            $this->remoteJid = $receiver . '@s.whatsapp.net';
        }

        return $this;
    }

    /**
     * Define la plantilla a usar.
     */
    public function plantilla($templateKey)
    {
        $this->type = 'plantilla';

        $plantillas = $this->plantillas();

        if (!array_key_exists($templateKey, $plantillas)) {
            throw new \Exception('La plantilla especificada no existe.');
        }

        $this->template = $plantillas[$templateKey];
        return $this;
    }

    /**
     * Define las variables para la plantilla.
     */
    public function variables(array $variables)
    {
        $this->variables = $variables;
        return $this;
    }

    /**
     * Establece el texto del mensaje.
     */
    public function texto($text)
    {
        $this->type = 'texto';
        $this->message = $text;
        return $this;
    }

    /**
     * Establece una imagen para enviar.
     */
    public function imagen($url, $caption = null, $mimetype = 'image/png', $fileName = null)
    {
        $this->type = 'media';
        $this->mediaType = 'image';
        $this->mediaUrl = $url;
        $this->caption = $caption;
        $this->mimetype = $mimetype;
        $this->fileName = $fileName ?? 'image.png';
        return $this;
    }

    /**
     * Establece un video para enviar.
     */
    public function video($url, $caption = null, $mimetype = 'video/mp4', $fileName = null)
    {
        $this->type = 'media';
        $this->mediaType = 'video';
        $this->mediaUrl = $url;
        $this->caption = $caption;
        $this->mimetype = $mimetype;
        $this->fileName = $fileName ?? 'video.mp4';
        return $this;
    }

    /**
     * Establece un documento para enviar.
     */
    public function documento($url, $caption = null, $mimetype = 'application/pdf', $fileName = null)
    {
        $this->type = 'media';
        $this->mediaType = 'document';
        $this->mediaUrl = $url;
        $this->caption = $caption;
        $this->mimetype = $mimetype;
        $this->fileName = $fileName ?? 'document.pdf';
        return $this;
    }

    /**
     * Establece una ubicación para enviar.
     */
    public function ubicacion($latitude, $longitude, $name, $address = null)
    {
        $this->type = 'ubicacion';
        $this->locationData = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'name' => $name,
            'address' => $address ?? $name,
        ];
        return $this;
    }

    /**
     * Establece un delay antes de enviar el mensaje (en milisegundos).
     */
    public function conDelay($milliseconds)
    {
        $this->delay = $milliseconds;
        return $this;
    }

    /**
     * Habilita la vista previa de enlaces.
     */
    public function conVistaPrevia($enabled = true)
    {
        $this->linkPreview = $enabled;
        return $this;
    }

    /**
     * Lista de plantillas disponibles.
     */
    public function plantillas()
    {
        return [
            // 'isbast_template_pago_arriendo_recordatorio' => "🔔 *Recordatorio de Pago de Arriendo* 🔔\n\nEstimado/a {{nombre_arrendatario}},\n\nEspero que se encuentre bien.\n\nLe escribo para recordarle que aún está pendiente el pago del arriendo correspondiente al mes de {{mes_anio}} por el inmueble ubicado en {{direccion_inmueble}}.\n\nLe agradeceremos realizar el pago a la brevedad para evitar recargos por mora y enviarnos el comprobante una vez efectuado. Le recordamos que puede realizar el pago a través de nuestro portal en el siguiente enlace:\n\n {{url_pago}}\n\n¡Gracias por su pronta atención! 📌",
            'isbast_template_pago_arriendo_mora' => "🔔 *Retraso en el Pago de Arriendo* 🔔\n\nEstimado/a {{nombre_arrendatario}},\nBuenos días, le habla {{nombre_encargada}} de Isbast, en relación con la regularización del pago de arriendo correspondiente a la propiedad ubicada en {{direccion_inmueble}}.\n\nLamentablemente, no contamos con el registro del pago correspondiente al mes de {{mes}}. En caso de que este pago haya sido efectuado, le agradeceríamos enviarnos el comprobante de pago a la mayor brevedad posible.\n\nAsimismo, le solicitamos enviar los comprobantes de pago correspondientes a luz, agua y gastos comunes, a fin de mantener un registro actualizado de las cuentas del inmueble.\n\nLe recordamos que puede realizar el pago a través de nuestro portal en el siguiente enlace:\n\n {{url_pago}}\n\n Agradezco su atención y quedo atenta a su pronta respuesta. Muchas gracias.\n",
            'isbast_template_pago_arriendo_recordatorio' => "Hola {{nombre_arrendatario}}! Espero que se encuentre bien. Su arriendo nos figura impago, regularice su situación a través del siguiente link:\n\n{{direccion_inmueble}}\n\n{{url_pago}}\n\nEvite acumular intereses, si tiene dudas, hay error en el link o gestiones pendientes, responda a este mensaje. Saludos!",
            'isbast_template_atencion_recordatorio' => "👋🏼 Hola, {{tiempo_horario}} {{nombre_completo}} \n\nTe comento que, para una mejor atención, las visitas presenciales se están realizando *solo con agendamiento previo*.\n\nPuedes agendar escribiendo a:\n📧 administracion2.isbast@isbast.com\n📧 administracion3.isbast@isbast.com\n\nO por WhatsApp a:\n\n +56 9 4227 7225\n\n +56 9 5669 1604\n\n +56 9 6156 0980\n\n⚠️ *Sin agenda confirmada no será posible atenderte.*\n\n¡Muchas gracias por tu comprensión! 😊",
            'isbast_template_contrato_vigencia' => "Hola {{nombre_completo}} \n\nEsperamos que te encuentres muy bien. Queremos informarte que tu contrato de arriendo correspondiente a la propiedad {{nombre_propiedad}}, finalizará el {{fecha_fin}}. \nSi estás interesado en renovar tu contrato, te recomendamos contactarte con nuestros ejecutivos de administración. \nEstaremos encantados de ayudarte 🤝 \n\nIsbast – Líderes en tecnología inmobiliaria",
        ];
    }

    /**
     * Envía el mensaje basado en la configuración.
     */
    public function enviar()
    {
        if (!$this->instance || !$this->apikey) {
            throw new \Exception('Las credenciales no están configuradas.');
        }

        if (!$this->receiver) {
            throw new \Exception('El receptor del mensaje no está definido.');
        }

        // Determinar qué método de envío usar según el tipo
        switch ($this->type) {
            case 'plantilla':
            case 'texto':
                return $this->enviarTexto();

            case 'media':
                return $this->enviarMedia();

            case 'ubicacion':
                return $this->enviarUbicacion();

            default:
                throw new \Exception('Tipo de mensaje no definido o no soportado.');
        }
    }

    /**
     * Envía un mensaje de texto usando el endpoint /sendText
     */
    private function enviarTexto()
    {
        $endpoint = self::BASE_URL . "/message/sendText/{$this->instance}";

        // Si es plantilla, reemplazar variables
        if ($this->type === 'plantilla') {
            if (!$this->template) {
                throw new \Exception('No se ha definido la plantilla.');
            }

            $mensaje = $this->template;
            foreach ($this->variables as $key => $value) {
                $mensaje = str_replace("{{{$key}}}", $value, $mensaje);
            }
            $this->message = $mensaje;
        }

        if (!$this->message) {
            throw new \Exception('El mensaje de texto no está definido.');
        }

        $payload = [
            'number' => $this->receiver,
            'text' => $this->message,
        ];

        // Parámetros opcionales
        if ($this->delay) {
            $payload['delay'] = $this->delay;
        }
        if ($this->linkPreview) {
            $payload['linkPreview'] = true;
        }

        return $this->ejecutarRequest($endpoint, $payload);
    }

    /**
     * Envía media (imagen, video, documento) usando el endpoint /sendMedia
     */
    private function enviarMedia()
    {
        $endpoint = self::BASE_URL . "/message/sendMedia/{$this->instance}";

        if (!$this->mediaUrl) {
            throw new \Exception('La URL del archivo no está definida.');
        }

        $payload = [
            'number' => $this->receiver,
            'mediatype' => $this->mediaType,
            'media' => $this->mediaUrl,
            'mimetype' => $this->mimetype,
            'fileName' => $this->fileName,
        ];

        // Parámetros opcionales
        if ($this->caption) {
            $payload['caption'] = $this->caption;
        }
        if ($this->delay) {
            $payload['delay'] = $this->delay;
        }
        if ($this->linkPreview) {
            $payload['linkPreview'] = true;
        }

        return $this->ejecutarRequest($endpoint, $payload);
    }

    /**
     * Envía una ubicación usando el endpoint /sendLocation
     */
    private function enviarUbicacion()
    {
        $endpoint = self::BASE_URL . "/message/sendLocation/{$this->instance}";

        if (empty($this->locationData)) {
            throw new \Exception('Los datos de ubicación no están definidos.');
        }

        $payload = [
            'number' => $this->receiver,
            'latitude' => $this->locationData['latitude'],
            'longitude' => $this->locationData['longitude'],
            'name' => $this->locationData['name'],
            'address' => $this->locationData['address'],
        ];

        // Parámetros opcionales
        if ($this->delay) {
            $payload['delay'] = $this->delay;
        }

        return $this->ejecutarRequest($endpoint, $payload);
    }

    /**
     * Ejecuta la petición HTTP a la API de Evolution
     */
    private function ejecutarRequest($endpoint, $payload)
    {
        $response = Http::withHeaders([
            'apikey' => $this->apikey,
            'Content-Type' => 'application/json',
        ])->post($endpoint, $payload);

        // Validar respuesta
        if (!in_array($response->status(), [200, 201])) {
            $errorMessage = $response->body();
            $statusCode = $response->status();

            Log::channel('whatsapp-errores')->error('Error al enviar el mensaje', [
                'endpoint' => $endpoint,
                'telefono' => $this->receiver,
                'tipo' => $this->type,
                'status_code' => $statusCode,
                'error_mensaje' => $errorMessage,
                'fecha_error' => now()->format('Y-m-d H:i:s')
            ]);

            throw new \Exception("Error al enviar el mensaje (Código: {$statusCode}): {$errorMessage}");
        }

        return $response->json();
    }

    /**
     * Obtiene el contenido de una plantilla específica por su código.
     * Función estática para acceso directo sin instanciar la clase.
     */
    public static function getPlantilla($codigo, $variables = [])
    {
        // Crear instancia temporal para acceder al método plantillas()
        $helper = new self();
        $plantillas = $helper->plantillas();

        if (!array_key_exists($codigo, $plantillas)) {
            throw new \Exception("La plantilla '{$codigo}' no existe.");
        }

        $plantilla = $plantillas[$codigo];

        // Reemplazar variables en la plantilla
        foreach ($variables as $key => $value) {
            $placeholder = "{{" . $key . "}}";
            $plantilla = str_replace($placeholder, $value, $plantilla);
        }

        return $plantilla;
    }

    /**
     * Marca un mensaje como leído (doble check azul).
     * Método de instancia - Requiere ->para() antes.
     * Ejecución inmediata (no requiere ->enviar()).
     * 
     * @param string $messageId ID del mensaje a marcar como leído
     * @param bool $fromMe Indica si el mensaje es propio (default: false)
     * @return array Respuesta de la API
     */
    public function marcarComoLeido($messageId, $fromMe = false)
    {
        if (!$this->instance || !$this->apikey) {
            throw new \Exception('Las credenciales no están configuradas. Usa setNumero() primero.');
        }

        if (!$this->remoteJid) {
            throw new \Exception('El receptor no está definido. Usa para() primero.');
        }

        $endpoint = self::BASE_URL . "/chat/markMessageAsRead/{$this->instance}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'apikey' => $this->apikey,
        ])->timeout(60)->post($endpoint, [
            'readMessages' => [
                [
                    'remoteJid' => $this->remoteJid,
                    'fromMe' => $fromMe,
                    'id' => $messageId
                ]
            ]
        ]);

        if (!in_array($response->status(), [200, 201])) {
            $errorMessage = $response->body();
            $statusCode = $response->status();

            Log::channel('whatsapp-errores')->error('Error al marcar mensaje como leído', [
                'endpoint' => $endpoint,
                'remote_jid' => $this->remoteJid,
                'message_id' => $messageId,
                'status_code' => $statusCode,
                'error_mensaje' => $errorMessage,
                'fecha_error' => now()->format('Y-m-d H:i:s')
            ]);

            throw new \Exception("Error al marcar mensaje como leído (Código: {$statusCode}): {$errorMessage}");
        }

        return $response->json();
    }

    /**
     * Marca un mensaje como leído (doble check azul).
     * Método estático (legacy) - Acepta parámetros directos.
     * 
     * @param string|int $numeroWhatsappId ID del registro NumeroWhatsapp
     * @param string $remoteJid JID del chat (ej: "59160268333@s.whatsapp.net")
     * @param string $messageId ID del mensaje a marcar como leído
     * @param bool $fromMe Indica si el mensaje es propio (default: false)
     * @return array Respuesta de la API
     */
    public static function marcarComoLeidoDirecto($numeroWhatsappId, $remoteJid, $messageId, $fromMe = false)
    {
        $numeroWhatsapp = NumeroWhatsapp::find($numeroWhatsappId);

        if (!$numeroWhatsapp) {
            throw new \Exception('Registro de WhatsApp no encontrado.');
        }

        $endpoint = self::BASE_URL . "/chat/markMessageAsRead/{$numeroWhatsapp->app_key}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'apikey' => $numeroWhatsapp->auth_key,
        ])->timeout(60)->post($endpoint, [
            'readMessages' => [
                [
                    'remoteJid' => $remoteJid,
                    'fromMe' => $fromMe,
                    'id' => $messageId
                ]
            ]
        ]);

        if (!in_array($response->status(), [200, 201])) {
            $errorMessage = $response->body();
            $statusCode = $response->status();

            Log::channel('whatsapp-errores')->error('Error al marcar mensaje como leído', [
                'endpoint' => $endpoint,
                'remote_jid' => $remoteJid,
                'message_id' => $messageId,
                'status_code' => $statusCode,
                'error_mensaje' => $errorMessage,
                'fecha_error' => now()->format('Y-m-d H:i:s')
            ]);

            throw new \Exception("Error al marcar mensaje como leído (Código: {$statusCode}): {$errorMessage}");
        }

        return $response->json();
    }

    /**
     * Muestra el indicador "escribiendo..." en el chat.
     * Método de instancia - Requiere ->para() antes.
     * Despacha Job asíncrono (no bloquea, ejecuta en background).
     * 
     * @param int $delay Duración en milisegundos (default: 5000)
     * @return $this Para permitir chaining si se necesita
     */
    public function mostrarEscribiendo($delay = 5000)
    {
        if (!$this->instance || !$this->apikey) {
            throw new \Exception('Las credenciales no están configuradas. Usa setNumero() primero.');
        }

        if (!$this->receiver) {
            throw new \Exception('El receptor no está definido. Usa para() primero.');
        }

        // Obtener el ID del NumeroWhatsapp desde el app_key (instance)
        $numeroWhatsapp = NumeroWhatsapp::where('app_key', $this->instance)->first();

        if (!$numeroWhatsapp) {
            throw new \Exception('No se pudo encontrar el registro de NumeroWhatsapp.');
        }

        // Despachar Job asíncrono
        \App\Jobs\MostrarServicioEscribiendoRespuestaJob::dispatch(
            'texto',
            $delay,
            $this->receiver,
            $numeroWhatsapp->id
        )->onQueue('whatsapp_queue');

        return $this;
    }

    /**
     * Muestra el indicador "grabando audio..." en el chat.
     * Método de instancia - Requiere ->para() antes.
     * Despacha Job asíncrono (no bloquea, ejecuta en background).
     * 
     * @param int $delay Duración en milisegundos (default: 5000)
     * @return $this Para permitir chaining si se necesita
     */
    public function mostrarGrabando($delay = 5000)
    {
        if (!$this->instance || !$this->apikey) {
            throw new \Exception('Las credenciales no están configuradas. Usa setNumero() primero.');
        }

        if (!$this->receiver) {
            throw new \Exception('El receptor no está definido. Usa para() primero.');
        }

        // Obtener el ID del NumeroWhatsapp desde el app_key (instance)
        $numeroWhatsapp = NumeroWhatsapp::where('app_key', $this->instance)->first();

        if (!$numeroWhatsapp) {
            throw new \Exception('No se pudo encontrar el registro de NumeroWhatsapp.');
        }

        // Despachar Job asíncrono
        \App\Jobs\MostrarServicioEscribiendoRespuestaJob::dispatch(
            'audio',
            $delay,
            $this->receiver,
            $numeroWhatsapp->id
        )->onQueue('whatsapp_queue');

        return $this;
    }

    /**
     * Muestra el indicador "escribiendo..." en el chat.
     * Método estático (legacy) - Acepta parámetros directos.
     * 
     * @param string|int $numeroWhatsappId ID del registro NumeroWhatsapp
     * @param string $destino Número del destinatario (ej: "59160268333")
     * @param int $delay Duración en milisegundos (default: 5000)
     * @return array Respuesta de la API
     */
    public static function mostrarEscribiendoDirecto($numeroWhatsappId, $destino, $delay = 5000)
    {
        return self::enviarPresencia($numeroWhatsappId, $destino, 'composing', $delay);
    }

    /**
     * Muestra el indicador "grabando audio..." en el chat.
     * Método estático (legacy) - Acepta parámetros directos.
     * 
     * @param string|int $numeroWhatsappId ID del registro NumeroWhatsapp
     * @param string $destino Número del destinatario (ej: "59160268333")
     * @param int $delay Duración en milisegundos (default: 5000)
     * @return array Respuesta de la API
     */
    public static function mostrarGrabandoDirecto($numeroWhatsappId, $destino, $delay = 5000)
    {
        return self::enviarPresencia($numeroWhatsappId, $destino, 'recording', $delay);
    }

    /**
     * Envía una presencia (typing/recording) al chat.
     * Método privado usado por mostrarEscribiendo() y mostrarGrabando().
     * 
     * @param string|int $numeroWhatsappId ID del registro NumeroWhatsapp
     * @param string $destino Número del destinatario
     * @param string $presence Tipo de presencia: 'composing' o 'recording'
     * @param int $delay Duración en milisegundos
     * @return array Respuesta de la API
     */
    private static function enviarPresencia($numeroWhatsappId, $destino, $presence, $delay)
    {
        $numeroWhatsapp = NumeroWhatsapp::find($numeroWhatsappId);

        if (!$numeroWhatsapp) {
            throw new \Exception('Registro de WhatsApp no encontrado.');
        }

        // Normalizar número: quitar el + si existe
        $destino = ltrim($destino, '+');

        $endpoint = self::BASE_URL . "/chat/sendPresence/{$numeroWhatsapp->app_key}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'apikey' => $numeroWhatsapp->auth_key,
        ])->timeout(60)->post($endpoint, [
            'number' => $destino,
            'delay' => $delay,
            'presence' => $presence
        ]);

        if (!in_array($response->status(), [200, 201])) {
            $errorMessage = $response->body();
            $statusCode = $response->status();

            Log::channel('whatsapp-errores')->error('Error al enviar presencia', [
                'endpoint' => $endpoint,
                'destino' => $destino,
                'presence' => $presence,
                'delay' => $delay,
                'status_code' => $statusCode,
                'error_mensaje' => $errorMessage,
                'fecha_error' => now()->format('Y-m-d H:i:s')
            ]);

            throw new \Exception("Error al enviar presencia (Código: {$statusCode}): {$errorMessage}");
        }

        return $response->json();
    }
}
