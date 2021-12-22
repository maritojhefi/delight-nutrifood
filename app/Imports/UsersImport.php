<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name' => $row['nombre'],
            'email' => str_replace(" ", "", strtolower($row['nombre'])) .'@gmail.com',
            'password' => str_replace(" ", "", strtolower($row['nombre'])).'-delight',
            
        ]);
    }
}
