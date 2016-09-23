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

/**
 * Class ChapterRepair
 * @desc repair single chapter
 * @package App\Jobs
 */
class ChapterRepair extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $chapter_id;
    private $force;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chapter_id, $force=false)
    {
        $this->chapter_id = $chapter_id;
        $this->force = $force;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('----- STARTING THE PROCESS FOR REPAIR CHAPTER -----');
        $dtStart = microtime_float();
        $chapter = Chapter::find($this->chapter_id);
        Biquge::repairChapter($chapter, $this->force);
        $dtEnd = microtime_float();
        Log::info('----- 耗时'.($dtEnd-$dtStart).'秒');
        Log::info('----- FINISHED THE PROCESS FOR REPAIR CHAPTER -----');
    }
}
