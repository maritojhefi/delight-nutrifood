<?php

namespace Database\Seeders;

use App\Models\Subcategoria;
use Illuminate\Database\Seeder;

class SubcategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subcategoria::create([
            'nombre'=>'Frutos secos',
            'descripcion'=>'Frutos secos empaquetados hermeticamente con largo tiempo de duracion',
            'categoria_id'=>1
        ]);
        Subcategoria::create([
            'nombre'=>'Sandwichs',
            'descripcion'=>'Sandwichs preparados al momento de hacer pedido',
            'categoria_id'=>1
        ]);
        Subcategoria::create([
            'nombre'=>'Granel',
            'descripcion'=>'Jugos elaborados con frutas de temporada y variedad de ingredientes 100% naturales',
            'categoria_id'=>1
        ]);
        Subcategoria::create([
            'nombre'=>'Jugos Naturales',
            'descripcion'=>'Jugos elaborados con frutas de temporada y variedad de ingredientes 100% naturales',
            'categoria_id'=>1
        ]);
        Subcategoria::create([
            'nombre'=>'Panes',
            'descripcion'=>'Panes originales hechos por Delight',
            'categoria_id'=>2
        ]);
        Subcategoria::create([
            'nombre'=>'Licuados',
            'descripcion'=>'Licuados de diferentes tipos con variedad de toppings y frutas/vegetales',
            'categoria_id'=>3
        ]);
        Subcategoria::create([
            'nombre'=>'A granel',
            'descripcion'=>'Productos que se venden a granel',
            'categoria_id'=>3
        ]);
        Subcategoria::create([
            'nombre'=>'Almuerzos',
            'descripcion'=>'Diferentes platillos con ingredientes personalizados',
            'categoria_id'=>3
        ]);
    }
}
