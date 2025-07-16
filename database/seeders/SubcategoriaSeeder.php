<?php

namespace Database\Seeders;

use App\Models\Subcategoria;
use App\Helpers\GlobalHelper;
use Illuminate\Database\Seeder;

class SubcategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array=[
            'Planes y paquetes de alimentacion'=>2,
            'Almuerzos'=>2,
            'Horneaditos 100% integrales'=>3,
            'Panes 100% integrales'=>3,
            'Reposteria 100% saludable'=>3,
            'Jugos y licuados naturales'=>2,
            'Suplementos Proteicos porcion'=>2,
            'Sandwiches Saludables'=>2,
            'Platos a la carta'=>2,
            'Bebidas Calientes'=>2,
            'Bebidas Frias-Frapuchinos'=>2,
            'Desayunos Saludables'=>2,
            'Desayunos Especiales'=>2,
            'Cajitas Especiales'=>2,
            'Ensalada y bowl de frutas'=>2,
            'Ensaladas'=>2,
            'Fast Food'=>2,
            'Aceites Saludables'=>1,
            'Cereales y granolas'=>1,
            'Pastas Integrales y sin gluten'=>1,
            'Frutos Secos y Liofilizados'=>1,
            'Harinas Nutraceuticas'=>1,
            'Endulzantes Saludables'=>1,
            'Suplementos'=>1,
            'Polvos Super Foods'=>1,
            'Algas Super Foods'=>1,
            'Hongos Super Foods'=>1,
            'Granos'=>1,
            'Galletas y snacks'=>1,
            'Leches Vegetales'=>1,
            'Yogures Probioticos Vegetales'=>1,
            'Yogures Griegos'=>1,
            'Yogures Probioticos'=>1,
            'Mantequillas y mermeladas'=>1,
            'Tes y Cafes'=>1,
            'Chocolates'=>1,
            'Barritas'=>1,
            'Otros Productos para cocinar'=>1,
            'Aguas Probioticas'=>1,
            'Bebidas Hidratantes'=>1,
            'Te frio y otras bebidas'=>1,
            'Cosmetica Natural'=>1,
            'Productos Medicinales-Natural'=>1,
            'Empaques Biodegradables'=>1,
            'Especias'=>1,
            'Otros Productos'=>1
        ];
        foreach($array as $nombre=>$categoria)
        {
            Subcategoria::create([
                'nombre'=>$nombre,
                'descripcion'=>'Productos '.GlobalHelper::getValorAtributoSetting('nombre_sistema'),
                'categoria_id'=>$categoria
            ]);
        }
        
        
    }
}
