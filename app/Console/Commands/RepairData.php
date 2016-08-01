<?php

namespace App\Console\Commands;

use App\Models\Chapter;
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
        $list = DB::table('chapter')
            ->select(DB::raw('novel_id, count(*) as num'))
            ->whereNotNull('content')
            ->groupBy('novel_id')
            ->get();
        foreach ($list as $key => $value) {
            $num = ceil($value->num/100);
            $i = 0;
            do{
                if($i>=$num){
                    break;
                }
                Biquge::repair($value->novel_id);
                $i++;
            }while(Chapter::where('novel_id', $value->novel_id)
                    ->whereNull('content')
                    ->count()
            );
        }
    }
}
