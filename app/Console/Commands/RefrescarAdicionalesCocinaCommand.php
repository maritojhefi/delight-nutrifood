<?php

namespace App\Console\Commands;

use App\Helpers\GlobalHelper;
use Illuminate\Console\Command;

class RefrescarAdicionalesCocinaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refrescar:adicionales-cocina';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresca los adicionales del dia para las ventas POS desde el menu de almuerzos';

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
        GlobalHelper::actualizarCarbosDisponibilidad();
    }
}
