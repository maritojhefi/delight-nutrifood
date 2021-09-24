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
            'descripcion'=>'Acceso a todo']);
            Role::create(
                ['nombre'=>'cajero',
                'descripcion'=>'Solo ventas']);
                Role::create(
                    ['nombre'=>'cocina',
                    'descripcion'=>'Vista a la cocina']);
                    Role::create(
                        ['nombre'=>'cliente',
                        'descripcion'=>'Consumidor final']);
                        Role::create(
                           ['nombre'=>'influencer',
                           'descripcion'=>'Cliente con privilegios']);
    }
}


