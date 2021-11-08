<?php

return [
    'name' => 'Delight-Nutrifood',
    'manifest' => [
        'name' => env('APP_NAME', 'Delight-Nutrifood'),
        'short_name' => 'DELIGHT',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation'=> 'portrait',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => '/images/icons/delight_logo.jpg',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/images/icons/delight_logo.jpg',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/images/icons/delight_logo.jpg',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/images/icons/delight_logo.jpg',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/images/icons/delight_logo.jpg',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/images/icons/delight_logo.jpg',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/images/icons/delight_logo.jpg',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/images/icons/delight_logo.jpg',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/images/icons/delight_logo.jpg',
            '750x1334' => '/images/icons/delight_logo.jpg',
            '828x1792' => '/images/icons/delight_logo.jpg',
            '1125x2436' => '/images/icons/delight_logo.jpg',
            '1242x2208' => '/images/icons/delight_logo.jpg',
            '1242x2688' => '/images/icons/delight_logo.jpg',
            '1536x2048' => '/images/icons/delight_logo.jpg',
            '1668x2224' => '/images/icons/delight_logo.jpg',
            '1668x2388' => '/images/icons/delight_logo.jpg',
            '2048x2732' => '/images/icons/delight_logo.jpg',
        ],
        'shortcuts' => [
            [
                'name' => 'Administrador',
                'description' => 'Ir al modo administrador',
                'url' => '/admin/ventas/index',
                'icons' => [
                    "src" => "/images/icons/delight_logo.jpg",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Mi Perfil',
                'description' => 'Ir a mi perfil',
                'url' => '/miperfil'
            ]
        ],
        'custom' => []
    ]
];
