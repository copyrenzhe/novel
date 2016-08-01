<?php

use App\Models\Novel;
use Illuminate\Database\Seeder;
use App\Repositories\Snatch\Biquge;

class OverNovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dtStart = microtime_float();
        $overNovels = Novel::over()->where('id', '>', 7646)->get();
        foreach($overNovels as $novel){
            Log::info("开始采集小说[$novel->id]:[$novel->name]");
            Biquge::updateNew($novel);
            $i = 0;
            do{
                if($i >= 10){
                    break;
                }
                Biquge::repair($novel->id);
                $i++;
            }while($novel->chapter()->whereNull('content')->count());
            Log::info("完结小说[$novel->id]:[$novel->name]更新完毕");
        }
        $overEnd = microtime_float();
        echo "完结小说已更新完毕\n";
        echo "耗时：".$overEnd-$dtStart."秒\n";
    }
}
