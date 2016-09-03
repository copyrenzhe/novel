<?php

use App\Models\Novel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Repositories\Snatch\Biquge;

class ContinueNovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dtStart = microtime_float();
        $continueNovels = Novel::continued()->whereBetween('id', [5851,10791])->get();
        foreach ($continueNovels as $novel){
            Log::info("开始采集小说[$novel->id]:[$novel->name]");
            Biquge::snatch($novel);
            Log::info("完结小说[$novel->id]:[$novel->name]更新完毕");
        }
        $continueEnd = microtime_float();
        echo "连载小说已更新完毕\n";
        echo "耗时：".($continueEnd-$dtStart)."秒\n";
    }
}
