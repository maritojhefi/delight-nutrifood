<?php

namespace App\Console\Commands;

use App\Models\Producto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EliminarStocksVaciosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eliminar:stocks-vacios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina registros de stocks vacios de productos en el sistema';

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
        DB::table('producto_sucursale')->where('cantidad', 0)->delete();
        return 0;
    }
}
