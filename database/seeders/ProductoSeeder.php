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
            ['nombre'=>'Licuado de agua',
            'subcategoria_id'=>6,
            'precio'=>'5',
            'detalle'=>'nada',
            'imagen'=>'delight_logo.jpg',
            'estado'=>'activo',
            'codigoBarra'=>'delight-123',
            'descuento'=>'4',
            'puntos'=>'1',
            ]);
            Producto::create(
                ['nombre'=>'Sandwich integral',
                'subcategoria_id'=>'2',
                'precio'=>'12',
                'detalle'=>'nada',
                'imagen'=>'delight_logo.jpg',
                'estado'=>'activo',
                'codigoBarra'=>'delight-123',
               
                'puntos'=>'2',
                ]);

                Producto::create(
                    ['nombre'=>'Almuerzo ejecutivo',
                    'subcategoria_id'=>'8',
                    'precio'=>'15.5',
                    'detalle'=>'almuerzo que incluye pollo asado, papa hervida etc',
                    'imagen'=>'delight_logo.jpg',
                    'estado'=>'activo',
                    'codigoBarra'=>'delight-123',
                   
                    'puntos'=>'3',
                    ]);

                    Producto::create(
                        ['nombre'=>'Almuerzo vegano',
                        'subcategoria_id'=>'8',
                        'precio'=>'20.50',
                        'detalle'=>'almuerzo con pollo a la plancha, brocoli, pure de almejas',
                        'imagen'=>'delight_logo.jpg',
                        'estado'=>'activo',
                        'codigoBarra'=>'delight-123',
                       
                        'puntos'=>'4',
                        ]);

                        Producto::create(
                            ['nombre'=>'Grano de cafe',
                            'subcategoria_id'=>'7',
                            'precio'=>'0.75',
                            'detalle'=>'nada',
                            'imagen'=>'delight_logo.jpg',
                            'estado'=>'activo',
                            'codigoBarra'=>'delight-123',
                            
                            'puntos'=>'1',
                            'medicion'=>'gramo'
                            ]);
    }
}
