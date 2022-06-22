<?php

namespace App\Helpers;

use GuzzleHttp\Client;



class WhatsappAPIHelper
{

    // public static function menuDiaSiguiente($fecha)
    // {
    //     return $dia;
    // }
    public static function saber_dia($nombredia) {
        //dd(date('N', strtotime($nombredia)));
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $fecha = $dias[date('N', strtotime($nombredia))];
        return $fecha;
    }
    public static function reenviarMensaje($idConversacion,$tipo,$cuerpo )
    {
        $string='{
            "type": "'.$tipo.'",
            "content": '.$cuerpo.'
            }';
        $cliente = new Client();
        $respuesta = $cliente->request('POST', 'https://conversations.messagebird.com/v1/conversations/' . $idConversacion . '/messages', [
            'headers' => [
                'Authorization' =>  'AccessKey ' . env('MESSAGEBIRD_KEY'),
                'Content-Type' => 'application/json'
            ],
            'body' => $string
        ]);
        $devolucion = json_decode($respuesta->getBody()->getContents());
        return $devolucion;
    }
    public static function timeago($date)
    {
        $timestamp = strtotime($date);

        $strTime = ['segundo', 'minuto', 'hora', 'dia', 'mes', 'año'];
        $length = ['60', '60', '24', '30', '12', '10'];

        $currentTime = time();
        if ($currentTime >= $timestamp) {
            $diff = time() - $timestamp;
            for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);
            return 'Hace ' . $diff . ' ' . $strTime[$i] . '(s)';
        }
    }
    public static function enviarTemplate(string $nombreTemplate, array $parametros, $destinatario, string $idioma)
    {
        $cliente = new Client();
        $arrayParametros = '';
        if ($parametros) {
            foreach ($parametros as $parametro) {
                $arrayParametros = $arrayParametros . '{"default":"' . $parametro . '"},';
            }
            $arrayParametros = substr($arrayParametros, 0, -1);
        }

        $respuesta = $cliente->request('POST', 'https://conversations.messagebird.com/v1/conversations/start', [
            'headers' => [
                'Authorization' =>  'AccessKey ' . env('MESSAGEBIRD_KEY'),
                'Content-Type' => 'application/json'
            ],
            'body' => '{
                "to": "' . $destinatario . '",
                "type": "hsm",
                
                "channelId": "' . env('MESSAGEBIRD_CHANNEL') . '",
                "content":{
                  "hsm": {
                    "namespace": "' . env('MESSAGEBIRD_NAMESPACE') . '",
                    "templateName": "' . $nombreTemplate . '",
                    "language": {
                    "policy": "deterministic",
                    "code": "' . $idioma . '"
                    },
                    "params": [' . $arrayParametros . '
                    ]
                  }
                }
              }'

        ]);
        $devolucion = json_decode($respuesta->getBody()->getContents());
        //WhatsappAPIHelper::historialConversacion($devolucion->id);
        //dd($devolucion);
        return $devolucion;
    }
    public static function enviarTemplateMultimedia(string $nombreTemplate, array $parametros, string $linkMultimedia, string $tipo, $destinatario, string $idioma)
    {
        $cliente = new Client();
        $arrayParametros = '';
        if ($parametros) {
            foreach ($parametros as $parametro) {
                $arrayParametros = $arrayParametros . '{"type": "text","text":"' . $parametro . '"},';
            }
            $arrayParametros = substr($arrayParametros, 0, -1);
        }

        $respuesta = $cliente->request('POST', 'https://conversations.messagebird.com/v1/conversations/start', [
            'headers' => [
                'Authorization' =>  'AccessKey ' . env('MESSAGEBIRD_KEY'),
                'Content-Type' => 'application/json'
            ],
            'body' => '{
                "to": "+591'.$destinatario.'",
                "type": "hsm",
                "channelId": "a95418f8-9490-4e57-bf64-bc11a48061a0",
                "content": {
                    "hsm": {
                        "namespace": "e5d38e32_c51a_4df5_837c_3c3bbba1a747",
                        "templateName": "'.$nombreTemplate.'",
                        "language": {
                            "policy": "deterministic",
                            "code": "'.$idioma.'"
                        },
                        "components": [
                            {
                                "type": "header",
                                "parameters": [
                                    {
                                        "type": "'.$tipo.'",
                                        "image": {
                                            "url": "'.$linkMultimedia.'"
                                        }
                                    }
                                ]
                            },
                            {
                                "type": "body",
                                "parameters": [
                                    '.$arrayParametros.'
                                ]
                            }
                        ]
                    }
                }
            }'

        ]);
        $devolucion = json_decode($respuesta->getBody()->getContents());
        //WhatsappAPIHelper::historialConversacion($devolucion->id);
        //dd($devolucion);
        return $devolucion;
    }

    public static function historialConversacion(string $idConversacion)
    {
        $cliente = new Client();
        $respuesta = $cliente->request('GET', 'https://conversations.messagebird.com/v1/conversations/' . $idConversacion . '/messages', [
            'headers' => [
                'Authorization' =>  'AccessKey ' . env('MESSAGEBIRD_KEY'),
                'Content-Type' => 'application/json'
            ]
        ]);
        $devolucion = json_decode($respuesta->getBody()->getContents());
        return $devolucion;
        //dd($devolucion);
    }

    public static function enviarMensajePersonalizado(string $idConversacion, string $tipo, string $contenido, string $caption="" )
    {
        $cliente = new Client();
        $respuesta = $cliente->request('POST', 'https://conversations.messagebird.com/v1/conversations/' . $idConversacion . '/messages', [
            'headers' => [
                'Authorization' =>  'AccessKey ' . env('MESSAGEBIRD_KEY'),
                'Content-Type' => 'application/json'
            ],
            'body' => '{
                "type": "'.$tipo.'",
                "content": '.WhatsappAPIHelper::armarBodyMensaje($tipo,$contenido,$caption).'
                }'
        ]);
        $devolucion = json_decode($respuesta->getBody()->getContents());
        return $devolucion;
        //dd(WhatsappAPIHelper::historialConversacion($idConversacion));
    }

    public static function armarBodyMensaje($tipo,$contenido, $caption)
    {
       if($caption!='')
       {
           if($tipo=='location')
           {
            $contenido2=$caption;
           }
           else
           {
            $contenido2='"caption": "'.$caption.'",';
           }
           
       }
       else
       {
           $contenido2='';
       }
        switch ($tipo) {
            case 'video':
                $textoContenido='{
                    "video": {
                        '.$contenido2.'
                        "url": "'.$contenido.'"
                    }
                }';
                break;
            case 'image':
                $textoContenido='{
                    "image": {
                        '.$contenido2.'
                        "url": "'.$contenido.'"
                    }
                }';
                break;
            case 'audio':
                $textoContenido='{
                    "audio": {
                        "url": "'.$contenido.'"
                    }
                }';
                break;
            case 'text':
                $textoContenido='{
                    "text": "'.$contenido.'"
                }';
                break;
            case 'location':
                $textoContenido='{
                    "location": {
                        "latitude": '.$contenido.',
                        "longitude": '.$contenido2.'
                    }
                }';
                break;
            case 'whatsappSticker':
                $textoContenido='{
                    "whatsappSticker": {
                        "link": '.$contenido.'"
                    }
                }';
                break;
            default:
            $textoContenido='{
                "text": "'.$contenido.'"
            }';
                break;
        }
        return $textoContenido;
    }
}
