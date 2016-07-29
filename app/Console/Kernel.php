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
//        Commands\Inspire::class,
        Commands\SnatchHourly::class,
        Commands\SnatchInit::class,
        Commands\SnatchDaily::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('snatch:updateHot')
            ->hourly()
            ->sendOutputTo(storage_path(). '/logs/novel.cron.updateHot.log');

        $schedule->command('snatch:initNovel')
            ->dailyAt('02:00')
            ->sendOutputTo(storage_path(). '/logs/novel.cron.initNovel.log');

        $schedule->command('snatch:updateAll')
            ->dailyAt('03:00')
            ->sendOutputTo(storage_path(). '/logs/novel.cron.updateAll.log');
    }
}
