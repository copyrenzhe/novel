<?php

namespace App\Listeners;

use App\Events\NovelView;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Redis;

class NovelViewListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NovelView  $event
     * @return void
     */
    public function handle(NovelView $event)
    {
        $chapter = $event->chapter;
        Redis::zIncrBy(config('cache.redis.view_total'), 1, $chapter->novel_id);
        Redis::zIncrBy(config('cache.redis.view_month'), 1, $chapter->novel_id);
        Redis::zIncrBy(config('cache.redis.view_week'), 1, $chapter->novel_id);

        //set expire
        $year = date('Y');
        $month = date('m');
        $weekday = date('w');
        if ($month == 12) {
            $nextYear = $year + 1;
            $nextMonth = 1;
        } else {
            $nextYear = $year;
            $nextMonth = $month + 1;
        }
        $expireMonth = strtotime(date("{$nextYear}-{$nextMonth}-1")) - 1 - time();
        if ($weekday == 0) {
            $expireWeek = strtotime(date('Y-m-d 23:59:59')) - time();
        } else {
            $expireWeek = strtotime(date('Y-m-d 23:59:59')) - time() + (7 - $weekday) * 24 * 60 * 60;
        }

        Redis::expire(self::REDIS_KEY_VIEW_MONTH, $expireMonth);
        Redis::expire(self::REDIS_KEY_VIEW_WEEK, $expireWeek);
    }
}
