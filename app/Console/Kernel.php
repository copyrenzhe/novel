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
        Commands\Inspire::class,
        Commands\SnatchHourly::class,
        Commands\SnatchInit::class,
        Commands\SnatchDaily::class,
        Commands\MailDaily::class,
        Commands\RepairData::class,
        Commands\SumOfChapters::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //每个小时更新热门小说
        $schedule->command('snatch:updateHot --queue')
                ->dailyAt('06:00')
                ->withoutOverlapping();

        //每天更新小说列表与小说信息
        $schedule->command('snatch:initNovel --queue')
                ->dailyAt('02:00')
                ->withoutOverlapping();

        //每天更新所有小说章节
        $schedule->command('snatch:update --queue')
                ->dailyAt('03:00');

        //每天更新所有小说章节数
        $schedule->command('sum:chapter --queue')
                ->dailyAt('12:00');

        //每天发送邮件
        $schedule->command('mail:daily')
                ->dailyAt('23:00');
    }
}
