<?php

namespace App\Console\Commands;

use App\Repositories\Snatch\Biquge;
use Illuminate\Console\Command;

class SnatchDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snatch:initnovel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) This command is used to init novel from snatch.';

    /**
     * Create a new command instance.
     *
     * @return void
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
            Biquge::init();
            $this->info('----- FINISHED THE PROCESS FOR INIT NOVEL -----');
        }catch (\Exception $e) {
            Log::error($e);
            $this->error('They received errors when running the process. View Log File.');
        }
    }
}
