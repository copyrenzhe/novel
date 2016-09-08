<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class Test extends Job implements ShouldQueue
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
        if(is_array($this->novel_id)){
            Log::info('id in('.implode(';', $this->novel_id).')');
        }elseif(is_string($this->novel_id)){
            Log::info('id ='.$this->novel_id);
        } else {
            $this->info('no novel_id');
        }
        Log::info($this->novel_id);
    }
}
