<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        Role::create(
            ['nombre'=>'admin',
            'descripcion'=>'Acceso a todo'],
            ['nombre'=>'cajero',
            'descripcion'=>'Solo ventas'],
            ['nombre'=>'cocina',
            'descripcion'=>'Vista a la cocina'],
            ['nombre'=>'cliente',
            'descripcion'=>'Consumidor final'],
            ['nombre'=>'influencer',
            'descripcion'=>'Cliente con privilegios'],
    );
    }
}
