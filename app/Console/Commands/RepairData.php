<?php

namespace App\Console\Commands;

use App\Models\Novel;
use App\Repositories\Snatch\Biquge;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RepairData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repair:data';

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
        $novels = Novel::all();
        foreach ($novels as $novel) {
            Biquge::repair($novel);
        }
    }
}
