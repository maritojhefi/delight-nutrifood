<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductosImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function model(array $row)
    {
       $nose=new Producto([
        'nombre' => $row['nombre'],
        'precio' => $row['precio'],
        'subcategoria_id' => $row['subcategoria_id'],
        'medicion'=>$row['medicion'],
      
    ]);
    
        return $nose;
    }
    
}
