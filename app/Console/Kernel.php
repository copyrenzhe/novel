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
        $subPath = Carbon::now()->year.'/'.Carbon::now()->month.'/'.Carbon::now()->day;
        //每十分钟，更新日志
        $schedule->call(function() use($subPath) {
            if(file_exists(storage_path(). '/logs/novel.cron.updateHot.tmp.log')){
                $temp_log = storage_path(). '/logs/novel.cron.updateHot.tmp.log';
                $update_log = storage_path(). '/logs/'.$subPath.'/updateHot.log';
                $foo = file_get_contents($temp_log);
                file_put_contents($update_log, $foo);
                //清空tmp.log
                file_put_contents($temp_log, '');
            }
        })->everyTenMinutes();

        //每个小时更新热门小说
        $schedule->command('snatch:updateHot')
                ->hourly()
                ->withoutOverlapping()
                ->sendOutputTo(storage_path(). '/logs/novel.cron.updateHot.tmp.log')
                ->withoutOverlapping();

        //每天更新小说列表与小说信息
        $schedule->command('snatch:initNovel')
                ->dailyAt('02:00')
                ->sendOutputTo(storage_path(). '/logs/'.$subPath.'./initNovel.log')
                ->withoutOverlapping();

        //每天更新所有小说章节
        $schedule->command('snatch:updateAll')
                ->dailyAt('03:00')
                ->sendOutputTo(storage_path(). '/logs/'.$subPath.'./updateAll.log');

        //每天更新所有小说章节数
        $schedule->command('sum:chapter')
                ->dailyAt('12:00');

        //每天发送邮件
        $schedule->command('mail:daily')
                ->dailyAt('23:00');
    }
}
