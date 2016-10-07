<?php

use App\Jobs\SnatchRepair;
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
        Log::useDailyFiles(storage_path().'/logs/repair', 5);
        for ($id =6000; $id<8000; $id++){
            dispatch(new SnatchRepair($id, true));
        }
        $continueEnd = microtime_float();
        echo "添加队列完毕\n";
        echo "耗时：".($continueEnd-$dtStart)."秒\n";
    }
}
