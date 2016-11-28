<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;

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
        Commands\CompareQidianNovel::class,
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
                ->twiceDaily(10, 18)
                ->withoutOverlapping();
//
        //每天更新所有小说章节
        $schedule->command('snatch:update --queue')
                ->dailyAt('03:00')
                ->withoutOverlapping();

        //每周与起点周榜对比
        $schedule->command('compare:qidian --queue')
                ->weekly()->saturdays()->at('17:00');
        $schedule->command('compare:qidian recom --queue')
                ->weekly()->saturdays()->at('18:00');
        $schedule->command('compare:qidian fin --queue')
                ->weekly()->saturdays()->at('19:00');

        //每月与起点月榜对比
        $schedule->command('compare:qidian click 2 --queue')
                ->monthlyOn(28, '20:00');
        $schedule->command('compare:qidian recom 2 --queue')
                ->monthlyOn(28, '21:00');
        $schedule->command('compare:qidian fin 2 --queue')
                ->monthlyOn(28, '22:00');
//
        //每天更新所有小说章节数
//        $schedule->command('sum:chapter --queue')
//                ->dailyAt('12:00');
//
//        //每天发送邮件
//        $schedule->command('mail:daily')
//                ->dailyAt('23:00');
    }
}
