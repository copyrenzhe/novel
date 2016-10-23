<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class SumOfChapters extends Job implements ShouldQueue
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
        Log::info('----- STARTING THE PROCESS FOR SUM OF CHAPTER -----');
        $dtStart = microtime_float();
        $novels = ($novel_id = $this->novel_id) ? Novel::whereIn('id', $this->novel_id)->get() : Novel::all();
        foreach ($novels as $novel) {
            $novel->chapter_num = Chapter::where('novel_id', $novel->id)->count();
            $novel->save();
        }
        $dtEnd = microtime_float();
        Log::info('----- 耗时'.($dtEnd-$dtStart).'秒');
        Log::info('----- FINISHED THE PROCESS FOR SUM OF CHAPTER -----');
    }
}
