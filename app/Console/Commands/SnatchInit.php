<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SnatchInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snatch:initNovel
                            {link : 链接}
                            {--queue : 是否进入队列}';

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
        if($this->option('queue')) {
            dispatch(new \App\Jobs\SnatchInit($this->argument('link')));
        } else {
            $snatch = new \App\Jobs\SnatchInit($this->argument('link'));
            $snatch->handle();
        }
    }
}
