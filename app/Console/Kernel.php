<?php

namespace App\Console;

use App\Models\Caja;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AperturaDiariaCaja::class,
        Commands\CambioEstadoPlanDiario::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $filePath='public/logs.txt';
        $schedule->command('apertura:diaria')
        ->dailyAt('01:00')->appendOutputTo($filePath);
        $schedule->command('plan:diario')
        ->hourly()->appendOutputTo($filePath);
        // $schedule->command('inspire')->hourly();
        /*$schedule->call(function () {
           
        })->daily();*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
