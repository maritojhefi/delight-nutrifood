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
            'nombre'=>'ECO-TIENDA',
            'descripcion'=>'Todos los productos disponibles en tiendas Delight para su venta al por mayor o menor'
          
        ]);
        Categoria::create([
            'nombre'=>'Cocina',
            'descripcion'=>'Productos elaborados en cocina con productos 100% naturales'
          
        ]);
        Categoria::create([
            'nombre'=>'Panaderia/Reposteria',
            'descripcion'=>'Todos los productos hechos en la seccion de panaderia Delight'
          
        ]);
        
    }
}
