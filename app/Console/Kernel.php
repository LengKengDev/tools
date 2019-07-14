<?php

namespace App\Console;

use App\Console\Commands\Crawler;
use App\Console\Commands\Export;
use App\Console\Commands\Jobcan;
use App\Console\Commands\RemoveFileCommand;
use App\Console\Commands\SendGreetingEmail;
use App\Console\Commands\SunGreeting;
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
        SendGreetingEmail::class,
        SunGreeting::class,
        RemoveFileCommand::class,
        Jobcan::class,
        Export::class,
        Crawler::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('activitylog:clean')->daily();
        $schedule->command('jobcan checkin')->weekdays()->dailyAt('09:30');
        $schedule->command('jobcan checkout')->weekdays()->dailyAt('19:00');
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
