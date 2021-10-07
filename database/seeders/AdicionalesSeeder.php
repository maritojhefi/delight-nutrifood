<?php

namespace Database\Seeders;

use App\Models\Adicionale;
use Illuminate\Database\Seeder;

class AdicionalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Adicionale::create([
            'nombre'=>'Piña',
            'precio'=>1.50
        ]);
        Adicionale::create([
            'nombre'=>'Manzana',
            'precio'=>1
        ]);
        Adicionale::create([
            'nombre'=>'Palta',
            'precio'=>1.50
        ]);
        Adicionale::create([
            'nombre'=>'Miel',
            'precio'=>3.50
        ]);
        Adicionale::create([
            'nombre'=>'Zanahoria',
            'precio'=>2
        ]);
        Adicionale::create([
            'nombre'=>'Remolacha',
            'precio'=>0.50
        ]);
        Adicionale::create([
            'nombre'=>'Chia',
            'precio'=>3
        ]);
        Adicionale::create([
            'nombre'=>'Nuez',
            'precio'=>1.50
        ]);
    }
}
