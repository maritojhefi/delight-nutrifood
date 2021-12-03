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
        $semana=['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'];
        $sopa=['Sopa de Mani','Sopa de Fideo','Sopa de Vegetales','Sopa de Quinua','Sopa de Trigo','Sopa de Avena'];
        $ejecutivo=['Bife a la plancha','Pollo al spiedo','Milaneza de Pollo','Pastel de fideo','Albondigas','Pollo a la plancha'];
        $dieta=['Ejemplo de muestra','Ejemplo de muestra','Ejemplo de muestra','Ejemplo de muestra','Ejemplo de muestra','Ejemplo de muestra'];
        $vegetariano=['Ejemplo de muestra','Ejemplo de muestra','Ejemplo de muestra','Ejemplo de muestra','Ejemplo de muestra','Ejemplo de muestra'];
        foreach($semana as $posicion=>$dia)
        {
            Almuerzo::create([
                'dia'=>$dia,
                'sopa'=>$sopa[$posicion],
                'ensalada'=>'Ensalada Variada',
                'ejecutivo'=>$ejecutivo[$posicion],
                'dieta'=>$dieta[$posicion],
                'vegetariano'=>$vegetariano[$posicion],
                'carbohidrato_1'=>'Papa asada',
                'carbohidrato_2'=>'Arroz Blanco',
                'carbohidrato_3'=>'Quinua',
                'jugo'=>'Linaza Tostada',
            ]);
        }
       
        
    }
}
