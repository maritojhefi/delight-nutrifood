<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        User::create(
            ['name'=>'Jacquelinne',
            'password'=>'delight123',
        'email'=>'jacquelinne@gmail.com',
        'role_id'=>1]);
          User::create(
        ['name'=>'Mario',
        'password'=>'jhefi123',
        'email'=>'maritojhefi@gmail.com',
         'role_id'=>1]);
        
    }
}
