<?php

namespace App\Console\Commands;

use App\Jobs\SnatchUpdate;
use Illuminate\Console\Command;

class SnatchDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snatch:update
                            {novel_id?* : 小说id}
                            {--queue : 是否进入队列}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) This command is used to update novel`s status & chapters.';

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
            dispatch(new SnatchUpdate($this->argument('novel_id')));
        } else {
            $snatch = new SnatchUpdate($this->argument('novel_id'));
            $snatch->handle();
        }
    }
}
