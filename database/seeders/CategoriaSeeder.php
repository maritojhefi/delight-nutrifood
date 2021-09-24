<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create([
            'nombre'=>'Tienda',
            'descripcion'=>'Todos los productos disponibles en tiendas delight para su venta al por mayor o menor'
          
        ]);
        Categoria::create([
            'nombre'=>'Panaderia',
            'descripcion'=>'Todos los productos hechos en la seccion de panaderia Delight'
          
        ]);
        Categoria::create([
            'nombre'=>'Cocina',
            'descripcion'=>'Platillos elaborados en cocina con tiempo de elaboracion variante de acuerdo al producto'
          
        ]);
    }
}
