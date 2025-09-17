<?php

namespace App\Helpers;

use App\Models\Tarjeta;
use App\Models\NumeroWhatsapp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    private $appKey;
    private $authKey;
    private $receiver;
    private $message;
    private $file;
    private $type; // texto, imagen, documento, plantilla
    private $template;
    private $variables = [];

    /**
     * Establece las credenciales a partir de la tarjeta seleccionada.
     */
    public static function setNumero(string $idRegistro)
    {
        $registro = NumeroWhatsapp::find($idRegistro);

        if (!$registro) {
            throw new \Exception('Tarjeta no encontrada o configuración inválida.');
        }

        $helper = new self();
        $helper->appKey = $registro->app_key;
        $helper->authKey = $registro->auth_key;

        return $helper;
    }

    /**
     * Define el receptor del mensaje.
     */
    public function para($receiver)
    {
        $this->receiver = $receiver;
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
     * Define el tipo de mensaje (texto, imagen, documento).
     */
    public function tipo($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Establece el texto del mensaje.
     */
    public function texto($text)
    {
        $this->message = $text;
        return $this;
    }

    /**
     * Establece la URL de la imagen.
     */
    public function imagen($url)
    {
        $this->file = $url;
        return $this;
    }

    /**
     * Establece la URL del documento.
     */
    public function documento($url)
    {
        $this->file = $url;
        return $this;
    }

    /**
     * Lista de plantillas disponibles.
     */
    public function plantillas()
    {
        return [
            'delight_template_verificar_numero' => "Recibimos una solicitud para verificar tu numero de teléfono en tu cuenta en Delight. \n\nTu código es: {{codigo}}",
        ];
    }

    /**
     * Envía el mensaje basado en la configuración.
     */
    public function enviar()
    {
        if (!$this->appKey || !$this->authKey) {
            throw new \Exception('Las credenciales no están configuradas.');
        }

        if (!$this->receiver) {
            throw new \Exception('El receptor del mensaje no está definido.');
        }

        $endpoint = 'https://whatsapp.macrobyte.cloud/api/create-message';
        $payload = [
            'appkey' => $this->appKey,
            'authkey' => $this->authKey,
            'to' => $this->receiver,
        ];

        if ($this->type === 'plantilla') {
            if (!$this->template) {
                throw new \Exception('No se ha definido la plantilla.');
            }

            // Reemplazar variables en la plantilla
            $mensaje = $this->template;
            foreach ($this->variables as $key => $value) {
                $mensaje = str_replace("{{{$key}}}", $value, $mensaje);
            }

            $payload['message'] = $mensaje;
        } else {
            if (!$this->message && !$this->file) {
                throw new \Exception('Debe proporcionar al menos un mensaje de texto o un archivo.');
            }

            if ($this->message) {
                $payload['message'] = $this->message;
            }

            if ($this->file) {
                $payload['file'] = $this->file;
            }
        }

        // Enviar solicitud HTTP
        $response = Http::asMultipart()->post($endpoint, $payload);

        if ($response->status() !== 200) {
            $errorMessage = $response->body();
            $statusCode = $response->status();
            Log::channel('whatsapp-errores')->error('Error al enviar el mensaje', [
                'telefono' => $this->receiver,
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
}
