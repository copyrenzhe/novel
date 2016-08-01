<?php

use Illuminate\Database\Seeder;
use App\Repositories\Snatch\Biquge;

class NovelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $dtStart = microtime_float();
        Biquge::init();
        $initEnd = microtime_float();
        echo "小说信息已更新完毕\n";
        echo "耗时：".$initEnd-$dtStart."秒\n";
    }
}
