<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Novel;
use App\Repositories\Snatch\Biquge;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class SnatchUpdate extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $novel_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($novel_id)
    {
        $this->novel_id = $novel_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::useDailyFiles(storage_path().'/logs/update', 5);
        $dtStart = microtime_float();
        if($novel_id = $this->novel_id){
            $novels = Novel::whereIn('id', $novel_id)->get();
        } else {
            $novels = Novel::continued()->get();
        }
        Log::info('----- STARTING THE PROCESS FOR UPDATE NOVELS -----');
        if($novels) {
            foreach ($novels as $novel) {
                $return = Biquge::updateNew($novel);
                if($return['code'])
                    Log::info("小说[{$novel->id}]：{$novel->name}更新成功");
                else
                    Log::info("小说[{$novel->id}]：{$novel->name}更新失败");
            }
            Log::info('----- FINISHED THE PROCESS FOR UPDATE NOVELS -----');
            $dtEnd = microtime_float();
            Log::info('----- 耗时'.($dtEnd-$dtStart).'秒');
        }
    }
}
