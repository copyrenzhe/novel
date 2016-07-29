<?php

namespace App\Console\Commands;

use Log;
use App\Repositories\Snatch\Biquge;
use Illuminate\Console\Command;

class SnatchInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snatch:initNovel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) This command is used to init novel from snatch.';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        try{
            $this->info('----- STARTING THE PROCESS FOR INIT NOVEL -----');
            $dtStart = microtime_float();
            Biquge::init();
            $dtEnd = microtime_float();
            $this->info('expire time '.$dtEnd-$dtStart.' seconds');
            $this->info('----- FINISHED THE PROCESS FOR INIT NOVEL -----');
        }catch (\Exception $e) {
            Log::error($e);
            $this->error('They received errors when running the process. View Log File.');
        }
    }
}
