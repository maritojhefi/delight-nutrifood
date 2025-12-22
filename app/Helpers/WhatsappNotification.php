<?php

namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;

class WhatsappNotification extends Exception
{
    //bases de peticion
    protected $urlbase = 'https://conversations.messagebird.com/v1/';

    //credenciales de whatsapp api
    public $channel;

    public $namespace;

    public $api_key;

    //parametros del mensaje
    protected $body;

    protected $header;

    protected $button;

    protected $buttons = [];

    protected $to;

    protected $type;

    protected $templateName;

    protected $endpoint;

    protected $lang;

    protected $policy;

    protected $typeContent;

    protected $content;

    protected $caption;

    protected $idConversation;

    const DEFAULT_URL = 1; // Valor predeterminado para URL

    const ALLOWED_URLS = [
        1 => 'conversations/start',
        2 => 'send',
        3 => 'conversations/{id}/messages',
    ];

    const DEFAULT_LANG = 'en'; // Valor predeterminado para Lang

    const ALLOWED_LANGS = [
        'en',
        'es',
        'pt_BR',
    ];

    public function addButton(string $type, string $text): self
    {
        $button = $this->buildJSON('button', [$text], $type);
        $this->buttons[] = $button;

        return $this;
    }

    public function __construct()
    {
        $this->channel = config('whatsapp-credentials.channel_id');
        $this->namespace = config('whatsapp-credentials.namespace');
        $this->api_key = config('whatsapp-credentials.api_key');
        $this->type = 'hsm';
        $this->policy = 'deterministic';
    }

    public static function startTemplate()
    {
        $instance = new self();
        $instance->endpoint(1);

        return $instance;
    }

    public static function sendTemplate()
    {
        $instance = new self();
        $instance->endpoint(2);

        return $instance;
    }

    public static function conversation()
    {
        $instance = new self();
        $instance->endpoint(3);

        return $instance;
    }

    public function typeContent(string $type): self
    {
        $this->typeContent = $type;

        return $this;
    }

    public function content(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function caption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function body(array $params): self
    {
        $this->body = $this->buildJSON('body', $params, 'text');

        return $this;
    }

    public function header(string $url, string $type): self
    {
        $this->header = $this->buildJSON('header', [$url], $type);

        return $this;
    }

    public function button(string $url): self
    {
        $this->button = $this->buildJSON('button', [$url], 'text');

        return $this;
    }

    public function to(string $number): self
    {
        $this->to = $number;

        return $this;
    }

    public function templateName(string $name): self
    {
        $this->templateName = $name;

        return $this;
    }

    public function lang(string $lang): self
    {
        if (!in_array($lang, self::ALLOWED_LANGS)) {
            throw new InvalidArgumentException('Invalid language');
        }
        $this->lang = $lang;

        return $this;
    }

    private function endpoint($params = self::DEFAULT_URL)
    {
        if (!array_key_exists($params, self::ALLOWED_URLS)) {
            throw new InvalidArgumentException('Invalid endpoint');
        }
        $this->endpoint = self::ALLOWED_URLS[$params];

        return $this;
    }

    public function withId($id)
    {
        $this->idConversation = $id;
        $this->endpoint = str_replace('{id}', $id, $this->endpoint);

        return $this;
    }

    public function send()
    {
        // Lógica de envío de notificación por WhatsApp
        $bodyData = $this->buildBodyRequest();
        $cliente = new Client();
        $respuesta = $cliente->request('POST', $this->urlbase . $this->endpoint, [
            'headers' => [
                'Authorization' => 'AccessKey ' . $this->api_key,
                'Content-Type' => 'application/json',
            ], 'body' => json_encode($bodyData),
        ]);
        $data = json_decode($respuesta->getBody()->getContents());
        //registrar los mensajes enviados
        if (isset($this->idConversation)) {
            registrarWhatsapp($this->idConversation,  null, 'Conversacion', null, true);
        } else {
            registrarWhatsapp($data->id, $this->to, 'Plantilla', $this->templateName, true);
        }
        return $data;
    }

    public function buildBodyRequest(): array
    {
        $endpoint = $this->endpoint;
        $endpointClear = str_replace($this->idConversation, '{id}', $endpoint);
        if ($endpointClear == self::ALLOWED_URLS[3]) { //si es que es respuesta de conversacion
            return $this->buildResponseConversation();
        } else { //si es que es envio de template
            $body['to'] = $this->to;
            $body['type'] = $this->type;
            if ($this->endpoint == self::ALLOWED_URLS[1]) {
                $body['channelId'] = $this->channel;
            } else {
                $body['from'] = $this->channel;
            }
            $body['content']['hsm']['namespace'] = $this->namespace;
            $body['content']['hsm']['templateName'] = $this->templateName;
            $body['content']['hsm']['language']['policy'] = $this->policy;
            $body['content']['hsm']['language']['code'] = $this->lang;
            $body['content']['hsm']['components'] = [];
            if ($this->header) {
                $body['content']['hsm']['components'][] = $this->header;
            }

            if ($this->body) {
                $body['content']['hsm']['components'][] = $this->body;
            }

            if ($this->button) {
                $body['content']['hsm']['components'][] = $this->button;
            }
            if (!empty($this->buttons)) {
                foreach ($this->buttons as $button) {
                    $body['content']['hsm']['components'][] = $button;
                }
            }

            return $body;
        }
    }

    public function buildResponseConversation()
    {
        $body['type'] = $this->typeContent;
        switch ($this->typeContent) {
            case 'image':
            case 'video':
                if (isset($this->caption)) {
                    $body['content'][$this->typeContent]['caption'] = $this->caption;
                }
                $body['content'][$this->typeContent]['url'] = $this->content[0];
                break;
            case 'audio':
                $body['content'][$this->typeContent]['url'] = $this->content[0];
                break;
            case 'whatsappSticker':
                $body['content'][$this->typeContent]['link'] = $this->content[0];
                break;
            case 'location':
                $body['content'][$this->typeContent]['latitude'] = $this->content[0];
                $body['content'][$this->typeContent]['longitude'] = $this->content[1];
                break;
            case 'file':
                $body['content'][$this->typeContent]['url'] = $this->content[0];
                break;
            case 'text':
                $body['content'][$this->typeContent] = $this->content[0];
                break;
            default:
                throw new InvalidArgumentException('Invalid content type');
                break;
        }

        return $body;
    }

    public function buildJSON(string $component, array $params, string $type): array
    {
        $array = [];
        $array['type'] = $component;

        switch ($component) {

            case 'button':
                if ($type == 'quick_reply') {
                    $array['sub_type'] = 'quick_reply';
                    $array['parameters'][0]['type'] = 'payload';
                    $array['parameters'][0]['payload'] = $params[0];
                } else if ($type == 'url') {
                    $array['sub_type'] = 'url';
                    $array['parameters'][0]['type'] = 'text';
                    $array['parameters'][0]['text'] = $params[0];
                } else {
                    throw new InvalidArgumentException('Invalid button type, valid options: "quick_reply","url"');
                }

                break;
            case 'body':
                $cont = 0;
                foreach ($params as $var) {
                    $array['parameters'][$cont]['type'] = 'text';
                    $array['parameters'][$cont]['text'] = (string)$var;
                    $cont++;
                }
                break;
            case 'header':
                if ($type == 'text') {
                    $array['parameters'][0]['type'] = 'text';
                    $array['parameters'][0]['text'] = (string)$params[0];
                } else {
                    $array['parameters'][0]['type'] = $type;
                    $array['parameters'][0][$type]['url'] = $params[0];
                }

                break;

            default:
                // code...
                break;
        }

        return $array;
    }
    public function returnContent()
    {
        return $this->buildResponseConversation();
    }
}
