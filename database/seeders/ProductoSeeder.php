<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Producto::create(
            ['nombre'=>'Naranja',
            'subcategoria_id'=>'1',
            'precio'=>'2',
            'detalle'=>'nada',
            'imagen'=>'delight_logo.jpg',
            'estado'=>'activo',
            'codigoBarra'=>'delight-123',
            'descuento'=>'20',
            'puntos'=>'1',
            ]);
            Producto::create(
                ['nombre'=>'Manzana',
                'subcategoria_id'=>'2',
                'precio'=>'4.30',
                'detalle'=>'nada',
                'imagen'=>'delight_logo.jpg',
                'estado'=>'activo',
                'codigoBarra'=>'delight-123',
                'descuento'=>'18',
                'puntos'=>'2',
                ]);

                Producto::create(
                    ['nombre'=>'Pera',
                    'subcategoria_id'=>'3',
                    'precio'=>'3.5',
                    'detalle'=>'nada',
                    'imagen'=>'delight_logo.jpg',
                    'estado'=>'activo',
                    'codigoBarra'=>'delight-123',
                   
                    'puntos'=>'3',
                    ]);

                    Producto::create(
                        ['nombre'=>'Sandia',
                        'subcategoria_id'=>'4',
                        'precio'=>'10.50',
                        'detalle'=>'nada',
                        'imagen'=>'delight_logo.jpg',
                        'estado'=>'activo',
                        'codigoBarra'=>'delight-123',
                        'descuento'=>'7',
                        'puntos'=>'4',
                        ]);

                        Producto::create(
                            ['nombre'=>'Grano de cafe',
                            'subcategoria_id'=>'5',
                            'precio'=>'1.75',
                            'detalle'=>'nada',
                            'imagen'=>'delight_logo.jpg',
                            'estado'=>'activo',
                            'codigoBarra'=>'delight-123',
                            'descuento'=>'7',
                            'puntos'=>'1',
                            'medicion'=>'gramo'
                            ]);
    }
}
