<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Caja;
use Illuminate\Console\Command;

class AperturaDiariaCaja extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apertura:diaria';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza apertura de caja en sucursal 1 con 0 bs de entrada';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ultimaCaja = Caja::where('sucursale_id',1)->whereDate('created_at',Carbon::today())->first();
        if(!$ultimaCaja)
        {
            Caja::create([
                'sucursale_id'=>1,
                'entrada'=>0,
                'estado'=>'abierto'
            ]);
            $this->info('Se hizo apertura de la caja en sucursal 1 con entrada de 0 bs');
        }
        else
        {
            $ultimaCaja->estado="abierto";
            $ultimaCaja->save();
            $this->info('Ya existe la caja abierta!');
        }
        
        
    }
}
