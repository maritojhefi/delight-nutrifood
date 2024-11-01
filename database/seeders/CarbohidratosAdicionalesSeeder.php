<?php

namespace Database\Seeders;

use App\Models\Adicionale;
use Illuminate\Database\Seeder;

class CarbohidratosAdicionalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Ejecutivo' => 'segundo_ejecutivo',
            'Dieta' => 'segundo_dieta',
            'Veggie' => 'segundo_veggie',
            'Carbohidrato 1' => 'carbohidrato_1',
            'Carbohidrato 2' => 'carbohidrato_2',
            'Carbohidrato 3' => 'carbohidrato_3'
        ];
        foreach ($array as $nombre => $codigoCocina) {
            Adicionale::firstOrCreate([
                'codigo_cocina' => $codigoCocina
            ], [
                'nombre' => $nombre,
                'cantidad' => 0,
                'precio' => 0,
                'contable' => 1
            ]);
        }
    }
}
