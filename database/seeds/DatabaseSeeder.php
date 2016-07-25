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
//        Biquge::init();
//        echo "小说信息已更新完毕\n";

        $overNovels = \App\Models\Novel::over()->get();
        foreach($overNovels as $novel){
            Biquge::update($novel);
        }
        echo "完结小说已更新完毕\n";

        $continueNovels = \App\Models\Novel::continued()->get();
        foreach ($continueNovels as $novel){
            Biquge::update($novel);
        }
        echo "连载小说已更新完毕\n";
    }
}
