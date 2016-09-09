<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Novel;
use App\Repositories\Snatch\Biquge;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class SnatchRepair extends Job implements ShouldQueue
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
        Log::useDailyFiles(storage_path().'/logs/repair', 5);
        Log::info('----- STARTING THE PROCESS FOR REPAIR NOVEL -----');
        $dtStart = microtime_float();
        if($novel_id = $this->novel_id){
            if(is_array($novel_id))
                $novels = Novel::whereIn('id', $novel_id)->get();
            else
                $novels = Novel::where('id', $novel_id)->get();
        } else {
            $novels = Novel::all();
        }
        foreach ($novels as $novel) {
            Biquge::repair($novel);
        }
        $dtEnd = microtime_float();
        Log::info('----- 耗时'.($dtEnd-$dtStart).'秒');
        Log::info('----- FINISHED THE PROCESS FOR REPAIR NOVELS -----');
    }
}
