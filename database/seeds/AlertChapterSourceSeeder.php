<?php

use App\Jobs\AlertSource;
use App\Models\Novel;
use Illuminate\Database\Seeder;

class AlertChapterSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dtStart = microtime_float();
        $novels = Novel::where('id', '<', 643)->get();
        foreach ($novels as $novel) {
            dispatch(new AlertSource($novel));
        }
        $continueEnd = microtime_float();
        echo "修改小说源结束\n";
        echo "耗时：".($continueEnd-$dtStart)."秒\n";
    }
}
