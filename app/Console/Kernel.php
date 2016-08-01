<?php

namespace App\Console;

use Carbon\Carbon;
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
        Commands\MailDaily::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            $foo = file_get_contents(storage_path(). '/logs/novel.cron.updateHot.tmp.log');
            file_put_contents(storage_path(). '/logs/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.Carbon::now()->day.'.updateHot.log', $foo);
        })->everyTenMinutes();

        $schedule->command('snatch:updateHot')
            ->hourly()
            ->withoutOverlapping()
            ->sendOutputTo(storage_path(). '/logs/novel.cron.updateHot.tmp.log');

        $schedule->command('snatch:initNovel')
            ->dailyAt('02:00')
            ->sendOutputTo(storage_path(). '/logs/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.Carbon::now()->day.'.initNovel.log');

        $schedule->command('snatch:updateAll')
            ->dailyAt('03:00')
            ->sendOutputTo(storage_path(). '/logs/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.Carbon::now()->day.'.updateAll.log');

        $schedule->command('mail:daily')
            ->dailyAt('23:00');
    }
}
