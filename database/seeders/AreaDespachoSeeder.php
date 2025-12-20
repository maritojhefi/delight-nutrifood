<?php

namespace Database\Seeders;

use App\Models\Sucursale;
use App\Models\AreaDespacho;
use Illuminate\Database\Seeder;

class AreaDespachoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sucursalId = Sucursale::first()->id;
        AreaDespacho::firstOrCreate([
            'nombre_area' => 'Cocina',
            'codigo_area' => 'cocina',
            'descripcion' => 'Area de cocina',
            'activo' => true,
            'sucursale_id' => $sucursalId,
        ]);
        AreaDespacho::firstOrCreate([
            'nombre_area' => 'Nutribar',
            'codigo_area' => 'nutribar',
            'descripcion' => 'Area de nutribar',
            'activo' => true,
            'sucursale_id' => $sucursalId,
        ]);
    }
}
