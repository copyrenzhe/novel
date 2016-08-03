<?php

use Illuminate\Database\Seeder;
use App\Repositories\Snatch\Biquge;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
//        $this->call(NovelTableSeeder::class);
//
//        $this->call(OverNovelSeeder::class);
//
        $this->call(ContinueNovelSeeder::class);
//
//        $dtStart = microtime_float();
//        $continueNovels = \App\Models\Novel::continued()->get();
//        foreach ($continueNovels as $novel){
//            Biquge::updateNew($novel);
//            $i = 0;
//            do{
//                if($i >= 10){
//                    break;
//                }
//                Biquge::repair($novel->id);
//                $i++;
//            }while($novel->chapter()->whereNull('content')->count());
//        }
//        $continueEnd = microtime_float();
//        echo "连载小说已更新完毕\n";
//        echo "耗时：".$continueEnd-$dtStart."秒\n";
    }
}
