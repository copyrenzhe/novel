<?php

use App\Models\Novel;
use App\Repositories\Snatch\Biquge;
use Illuminate\Database\Seeder;

class RepairNovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dtStart = microtime_float();
        $continueNovels = Novel::continued()->where('chapter_num', 0)->get();
        foreach ($continueNovels as $novel){
            Log::info("开始采集小说[$novel->id]:[$novel->name]");
            Biquge::snatch($novel);
            Log::info("小说[$novel->id]:[$novel->name]更新完毕");
        }
        $continueEnd = microtime_float();
        echo "小说已修复完毕\n";
        echo "耗时：".($continueEnd-$dtStart)."秒\n";
    }
}
