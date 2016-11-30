<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\Snatch\Snatch;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class SnatchInit extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $link;
    private $source;

    /**
     * Create a new job instance.
     *
     * @param $link
     * @param string $source
     */
    public function __construct($link, $source='biquge')
    {
        $this->link = $link;
        $this->source = $source;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('----- STARTING THE PROCESS FOR INIT NOVEL FROM LINK:'.$this->link. '-----');
        $dtStart = microtime_float();
        $instance = ucfirst($this->source);
        $novel = $instance::init($this->link);
        if($novel) {
            Log::info("小说[$novel->id]:[$novel->name] 已初始化完毕");
            $instance::snatch($novel);
            Log::info("小说[$novel->id]:[$novel->name] 已采集完毕");
        }
        $dtEnd = microtime_float();
        Log::info('expire time '.($dtEnd-$dtStart).' seconds');
        Log::info('----- FINISHED THE PROCESS FOR INIT NOVEL FROM LINK:'.$this->link. '-----');
    }
}
