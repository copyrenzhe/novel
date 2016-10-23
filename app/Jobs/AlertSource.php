<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Novel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class AlertSource extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $novel;

    /**
     * Create a new job instance.
     *
     * @param $novel
     */
    public function __construct(Novel $novel)
    {
        $this->novel = $novel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('----- STARTING THE PROCESS FOR ALERT SOURCE -----');
        $dtStart = microtime_float();
        foreach($this->novel->chapter as $chapter) {
            $linkArr = explode('/', $chapter->source_link);
            $chapter->source_link = 'http://www.qu.la'. $this->novel->source_link . end($linkArr);
            $chapter->save();
        }
        $dtEnd = microtime_float();
        Log::info("----- 小说{$this->novel->id}源已处理,耗时".($dtEnd-$dtStart)."秒");
        Log::info('----- FINISHED THE PROCESS FOR ALERT SOURCE -----');
    }
}
