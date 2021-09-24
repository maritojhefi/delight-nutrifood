<?php

namespace Database\Seeders;

use App\Models\Sucursale;
use Illuminate\Database\Seeder;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sucursale::create([
            'nombre'=>'Sucursal Central',
            'direccion'=>'calle Campero y 15 de abril',
            'telefono'=>'+59178227629'
          
        ]);
        Sucursale::create([
            'nombre'=>'Sucursal 2',
            'direccion'=>'Av. La Paz y Circunvalacion',
            'telefono'=>'+59178227629'
          
        ]);
    }
}
