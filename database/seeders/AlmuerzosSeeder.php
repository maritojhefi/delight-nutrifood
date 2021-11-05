<?php

namespace Database\Seeders;

use App\Models\Almuerzo;
use Illuminate\Database\Seeder;

class AlmuerzosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Almuerzo::create([
            'dia'=>'Lunes',
            'sopa'=>'Sopa de avena',
            'ensalada'=>'ensalada variada',
            'ejecutivo'=>'',
            'dieta'=>'',
            'vegetariano'=>'',
            'carbohidrato_1'=>'papa asada',
            'carbohidrato_2'=>'arroz blanco',
            'carbohidrato_3'=>'quinua',
            'jugo'=>'linaza tostada',
        ]);
        Almuerzo::create([
            'dia'=>'Martes',
            'sopa'=>'Sopa de avena',
            'ensalada'=>'ensalada variada',
            'ejecutivo'=>'',
            'dieta'=>'',
            'vegetariano'=>'',
            'carbohidrato_1'=>'papa asada',
            'carbohidrato_2'=>'arroz blanco',
            'carbohidrato_3'=>'quinua',
            'jugo'=>'linaza tostada',
        ]);
        Almuerzo::create([
            'dia'=>'Miercoles',
            'sopa'=>'Sopa de avena',
            'ensalada'=>'ensalada variada',
            'ejecutivo'=>'',
            'dieta'=>'',
            'vegetariano'=>'',
            'carbohidrato_1'=>'papa asada',
            'carbohidrato_2'=>'arroz blanco',
            'carbohidrato_3'=>'quinua',
            'jugo'=>'linaza tostada',
        ]);
        Almuerzo::create([
            'dia'=>'Jueves',
            'sopa'=>'Sopa de avena',
            'ensalada'=>'ensalada variada',
            'ejecutivo'=>'',
            'dieta'=>'',
            'vegetariano'=>'',
            'carbohidrato_1'=>'papa asada',
            'carbohidrato_2'=>'arroz blanco',
            'carbohidrato_3'=>'quinua',
            'jugo'=>'linaza tostada',
        ]);
        Almuerzo::create([
            'dia'=>'Viernes',
            'sopa'=>'Sopa de avena',
            'ensalada'=>'ensalada variada',
            'ejecutivo'=>'',
            'dieta'=>'',
            'vegetariano'=>'',
            'carbohidrato_1'=>'papa asada',
            'carbohidrato_2'=>'arroz blanco',
            'carbohidrato_3'=>'quinua',
            'jugo'=>'linaza tostada',
        ]);
        Almuerzo::create([
            'dia'=>'Sabado',
            'sopa'=>'Sopa de avena',
            'ensalada'=>'ensalada variada',
            'ejecutivo'=>'',
            'dieta'=>'',
            'vegetariano'=>'',
            'carbohidrato_1'=>'papa asada',
            'carbohidrato_2'=>'arroz blanco',
            'carbohidrato_3'=>'quinua',
            'jugo'=>'linaza tostada',
        ]);
    }
}
