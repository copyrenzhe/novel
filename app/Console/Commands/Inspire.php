<?php

namespace App\Console\Commands;

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
        if(is_array($this->argument('novel_id'))){
            Log::info('id in('.implode(';', $this->argument('novel_id')).')');
        }elseif(is_string($this->argument('novel_id'))){
            Log::info('id ='.$this->argument('novel_id'));
        } else {
            $this->info('no novel_id');
        }
        Log::info($this->argument('novel_id'));
        Log::info($this->option('queue'));
    }
}
