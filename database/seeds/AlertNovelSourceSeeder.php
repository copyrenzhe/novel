<?php

use App\Models\Novel;
use Illuminate\Database\Seeder;

class AlertNovelSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dtStart = microtime_float();
        $novels = Novel::all();
        foreach ($novels as $novel) {
            $novel->source = 'biquge';
            $novel->source_link = strstr($novel->source_link, '/book');
            $novel->save();
        }
        $continueEnd = microtime_float();
        echo "修改小说源结束\n";
        echo "耗时：".($continueEnd-$dtStart)."秒\n";
    }
}
