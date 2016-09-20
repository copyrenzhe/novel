<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\Snatch\Biquge;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class SnatchInit extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::useDailyFiles(storage_path().'/logs/novel', 5);
        Log::info('----- STARTING THE PROCESS FOR INIT NOVEL FROM LINK:'.$this->link. '-----');
        $dtStart = microtime_float();
        Biquge::init($this->link);
        $dtEnd = microtime_float();
        Log::info('expire time '.($dtEnd-$dtStart).' seconds');
        Log::info('----- FINISHED THE PROCESS FOR INIT NOVEL FROM LINK:'.$this->link. '-----');
    }
}
