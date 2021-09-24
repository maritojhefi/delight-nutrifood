<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Sucursale;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\SucursalSeeder;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\SubcategoriaSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(SucursalSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(SubcategoriaSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        Producto::factory(100)->create();
        
    }
}
