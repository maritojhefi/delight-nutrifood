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

        $array=['con stevia','con azucar','sin endulzante','leche de coco','leche de vaca','leche de tarwi',
                'jengibre','curcuma','maca','algarrobo','spirulina','moringa','chia','linaza','pito quinua',
                'pito cañahua','pito amaranto','hojuelas de avena','granola','almendras','nuez','caju','sem. de calabaza','sem. de girasol',
                'mani','banana','manzana','piña','sandia','kiwi','manzana','acai','copoazu','tumbo','maracuya','carambola',   
                'frutilla','arandano','mora','frambuesa'          
    ];
    foreach($array as $lista)
    {
        Adicionale::create([
            'nombre'=>$lista,
            'precio'=>0
        ]);
    }
        
        
    }
}
