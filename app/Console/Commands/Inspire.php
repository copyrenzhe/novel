<?php

namespace App\Console\Commands;

use App\Jobs\Test;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Log;

class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire
                            {novel_id?* : 小说id}
                            {--queue : 是否进入队列}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        dispatch(new Test($this->argument('novel_id')));
        $test = new Test($this->argument('novel_id'));
        $test->handle();
//        $count = 100;
//        $bar = $this->output->createProgressBar($count);
//        for ($i=1; $i<$count; $i++){
//            sleep(1);
//            $bar->advance();
//        }
//        $bar->finish();
    }
}
