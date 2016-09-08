<?php

namespace App\Console\Commands;

use Log;
use App\Models\Novel;
use Illuminate\Console\Command;
use App\Repositories\Snatch\Biquge;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        //
        try{
            $this->info('----- STARTING THE PROCESS FOR UPDATE ALL NOVELS -----');
            $dtStart = microtime_float();
            if($novel_id = $this->argument('novel_id')){
                $novels = Novel::whereIn('id', $novel_id)->get();
            } else {
                $novels = Novel::continued()->get();
            }
            if($novels) {
                $this->info('All novels to be processed');
                foreach ($novels as $novel) {
                    $return = Biquge::updateNew($novel);
                    if($return['code'])
                        $this->info("小说[{$novel->id}]：{$novel->name}更新成功");
                    else
                        $this->info("小说[{$novel->id}]：{$novel->name}更新失败");
                }
                $this->info('----- FINISHED THE PROCESS FOR UPDATE ALL NOVELS -----');
                $dtEnd = microtime_float();
                $this->info('----- 耗时'.($dtEnd-$dtStart).'秒');
            }
        } catch (ModelNotFoundException $e) {
            Log::error($e);
            $this->error('They received errors when running the process. View Log File.');
        }
    }
}
