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
       
    }
}
