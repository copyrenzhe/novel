<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Console\Command;

class SumOfChapters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sum:chapter
                            {novel_id?* : 小说id}
                            {--queue : 是否进入队列}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) Sum of chapter`s number for every novel';

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
        if($this->option('queue')) {
            dispatch(new \App\Jobs\SumOfChapters($this->argument('novel_id')));
        } else {
            $snatch = new \App\Jobs\SumOfChapters($this->argument('novel_id'));
            $snatch->handle();
        }
    }
}
