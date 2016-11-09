<?php

use App\Jobs\SnatchInit;
use Illuminate\Database\Seeder;

class MingzhuNovelsInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dtStart = microtime_float();
        $mzhu = new \App\Repositories\Snatch\Mzhu();
        $links = $mzhu->getNovelList();
        foreach($links as $link){
            dispatch(new SnatchInit($link, 'mzhu'));
        }
        $continueEnd = microtime_float();
        echo "添加队列完毕\n";
        echo "耗时：".($continueEnd-$dtStart)."秒\n";
    }
}
