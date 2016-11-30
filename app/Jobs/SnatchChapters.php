<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Chapter;
use App\Models\Novel;
use App\Repositories\Snatch\Biquge;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class SnatchChapters extends Job implements ShouldQueue
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
        $dtStart = microtime_float();
        if($novel_id = $this->novel_id){
            if(is_array($novel_id))
                $novels = Novel::whereIn('id', $novel_id)->get();
            else
                $novels = Novel::where('id', $novel_id)->get();
        } else {
            $novels = Novel::continued()->get();
        }
        Log::info('----- STARTING THE PROCESS FOR SNATCH NOVELS -----');
        if($novels) {
            foreach ($novels as $novel) {
                Log::info('----采集前先清空章节----');
                Chapter::where('novel_id', $novel_id)->delete();
                Log::info('----清空完成，开始采集----');
                $source = ucfirst($novel->source);
                $return = $source::snatch($novel);
                if($return['code'])
                    Log::info("小说[{$novel->id}]：{$novel->name}采集成功");
                else
                    Log::info("小说[{$novel->id}]：{$novel->name}采集失败");
            }
            Log::info('----- FINISHED THE PROCESS FOR SNATCH NOVELS -----');
            $dtEnd = microtime_float();
            Log::info('----- 耗时'.($dtEnd-$dtStart).'秒');
        }
    }
}
