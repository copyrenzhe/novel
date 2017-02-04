<?php

namespace App\Jobs;

use App\Models\Chapter;
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

    private $chapter;
    private $force;

    /**
     * Create a new job instance.
     *
     * @param $chapter
     * @param bool $force
     */
    public function __construct($chapter, $force=false)
    {
        $this->chapter = $chapter;
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
        $source = ucfirst($this->chapter->novel->source);
        $source::repairChapter($this->chapter, $this->force);
        $dtEnd = microtime_float();
        Log::info('----- 耗时'.($dtEnd-$dtStart).'秒');
        Log::info('----- FINISHED THE PROCESS FOR REPAIR CHAPTER -----');
    }
}
