<?php

namespace App\Console;

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
        //
        \App\Console\Commands\getLineup::class,
        \App\Console\Commands\getLqSchedule::class,
        \App\Console\Commands\getLqSClass::class,
        \App\Console\Commands\getPlayerTech::class,
        \App\Console\Commands\getTodayLqSchedule::class,
        \App\Console\Commands\setData::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('sportsevent:getLqSchedule')->hourly();
        $schedule->command('sportsevent:getLqSClass')->everyMinute();
        $schedule->command('sportsevent:getLineup')->everyMinute();
        $schedule->command('sportsevent:getPlayerTech')->everyMinute();
        $schedule->command('sportsevent:getTodayLqSchedule')->everyMinute();
        $schedule->command('sportsevent:setData')->hourly();
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
