<?php

namespace App\Console\Commands;

use App\Models\Saldo;
use Illuminate\Console\Command;

class PasarIdSaldosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saldos:ajustar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $registros=Saldo::where('caja_id',null)->get();
        foreach($registros as $registro)
        {
            $registro->caja_id=$registro->venta2->caja_id;
            $registro->historial_ventas_id=$registro->historial_venta_id;
            $registro->save();
        }
        $this->line('se encontraron '.$registros->count().' registros');
        return Command::SUCCESS;
    }
}
