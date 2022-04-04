<?php

namespace App\Console;

use App\Console\Commands\AttachmentsCron;
use App\Console\Commands\CategoryCron;
use App\Console\Commands\SpacesCron;
use App\Console\Commands\SubcategoriesCron;
use App\Console\Commands\ZonesCron;
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
        CategoryCron::class,
        SubcategoriesCron::class,
        SpacesCron::class,
        ZonesCron::class,
        AttachmentsCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('category:cron')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('subcategories:cron')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('spaces:cron')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('zones:cron')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('building:cron')->everyTenMinutes()->withoutOverlapping();


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
