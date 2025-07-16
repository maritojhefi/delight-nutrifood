<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'nombre_sistema' => ['Delight', false],
            'nombre_empresa' => ['Nutri-Food/Eco-Tienda', false],
            'slogan' => ['Nutriendo Hábitos', false],


            'logo' => ['imagenes/delight/delight_logo.jpg', true],

            'logo_small' => ['imagenes/delight/logo2.png', true],
            'producto_default' => ['imagenes/delight/logo2.png', true],

            'logo_medium' => ['imagenes/delight/logodelight.png', true],
            'diseno_qr' => ['imagenes/delight/QR-DELIGHT.png', true],

            //usuario
            'mi_perfil_deligth' => ['imagenes/delight/21.jpeg', true],
            'gana_puntos' => ['imagenes/delight/8.jpeg', true],
            'dia_noche_inicio' => ['imagenes/delight/1.jpeg', true],
            'inicio_perfil' => ['imagenes/delight/4.jpeg', true],

            'inicio_disfruta' => ['imagenes/delight/2.jpeg', true],
            'subcategoria_default' => ['imagenes/delight/2.jpeg', true],

            'gif_pulse' => ['imagenes/delight/gifpulse7.gif', true],
            'planes_usuario' => ['imagenes/delight/9.jpeg', true],
            'mas_puntos' => ['imagenes/delight/8.jpeg', true],
            'bajo_construccion' => ['imagenes/delight/underconstruction.gif', true],

            'direccion' => ['Calle Campero y 15 de abril', false],
            'telefono' => ['78227629', false],
            'url_web' => [url('/'), false],
            'prefijo_codigo_barras' => ['delight', false],
            'nombre_foto_logo' => ['delight_logo.jpg', false],

            'url_facebook' => ['https://www.facebook.com/DelightNutriFoodEcoTienda', false],
            'url_whatsapp' => ['https://wa.link/ewfjau', false],
            'url_instagram' => ['https://www.instagram.com/delight_nutrifood_ecotienda/', false],
            'url_youtube' => ['https://www.youtube.com/channel/UC5MWq8AsnpRYocjfyg_LY8w', false],

        ];
        foreach ($array as $nombre => $valor) {
            Setting::updateOrCreate([
                'atributo' => $nombre
            ], [
                'valor' => $valor[0],
                'es_imagen' => $valor[1]
            ]);
        }
    }
}
