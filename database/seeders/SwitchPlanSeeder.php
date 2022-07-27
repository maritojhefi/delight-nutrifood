<?php

namespace Database\Seeders;

use App\Models\SwitchPlane;
use Illuminate\Database\Seeder;

class SwitchPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existe=SwitchPlane::all();
        if($existe->count()==0)
        {
            SwitchPlane::create();
        }
       
    }
}
