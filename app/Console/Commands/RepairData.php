<?php

namespace App\Console\Commands;

use App\Jobs\SnatchRepair;
use Illuminate\Console\Command;

class RepairData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snatch:repair
                            {novel_id?* : 小说id}
                            {--force : 是否强制}
                            {--queue : 是否进入队列}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) Repair data where content is null';

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
            dispatch(new SnatchRepair($this->argument('novel_id'), $this->option('force') ? true : false));
        } else {
            $snatch = new SnatchRepair($this->argument('novel_id'), $this->option('force') ? true : false);
            $snatch->handle();
        }
    }
}
