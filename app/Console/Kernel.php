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
        $logGeneral = 'public/logs.txt';
        $logsAdmin = 'public/logsAdmin.txt';
        $logsMenu = 'public/logsMenuWhatsapp.txt';
        $schedule->command('apertura:diaria')
            ->dailyAt('01:00');
        $schedule->command('plan:diario')
            ->hourly();
        $schedule->command('finalizarPlanTodos:diario')
            ->dailyAt('09:00');

        $schedule->command('eliminar:stocks-vacios')
            ->dailyAt('01:30');
        // $schedule->command('whatsapp:enviarMenu')
        //     ->twiceDaily(18, 20)->appendOutputTo($logsMenu);
        // $schedule->command('whatsapp:enviarMenuManana')
        //     ->dailyAt('08:00')->appendOutputTo($logsMenu);
        $schedule->command('bloquear:menu')
            ->weeklyOn(6, '15:00');
        $schedule->command('notificar:planExpirado')
            ->dailyAt('11:00');
        $schedule->command('refrescar:adicionales-cocina')
            ->dailyAt('01:00');
        // $schedule->command('actualizar:tickets')
        // ->everyMinute();

        // $schedule->command('whatsapp:enviarMenu')
        // ->everyThirtyMinutes()->appendOutputTo($filePath);

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
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
