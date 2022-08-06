<?php

namespace App\Console\Commands;

use App\Models\SwitchPlane;
use Illuminate\Console\Command;

class BloquearMenuSabadoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bloquear:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bloquea el menu para los clientes cada sabado en la tarde';

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
        $switcher=SwitchPlane::find(1);
        $switcher->activo=false;
        $switcher->save();
        $this->info('Se bloqueo el menu para los clientes');
    }
}
