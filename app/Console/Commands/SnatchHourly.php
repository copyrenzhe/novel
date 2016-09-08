<?php

namespace App\Console\Commands;

use Log;
use App\Models\Novel;
use App\Repositories\Snatch\Biquge;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SnatchHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snatch:updateHot
                            {number? : 按热度更新小说数量}
                            {--queue : 是否进入队列}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) This command is used to update hot novel`s chapters.';

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
            $number = $this->argument('number') ? intval($this->argument('number')) : 30;
            $this->info('----- STARTING THE PROCESS FOR UPDATE OF HOT LIMIT '.$number.' -----');
            $hotNovels = Novel::continued()->orderBy('hot', 'desc')->take($number)->get();
            if($hotNovels) {
                $this->info('Hot novels to be processed');
                foreach ($hotNovels as $hotNovel) {
                    $this->info("-- 开始更新小说[{$hotNovel->name}] --");
                    $return = Biquge::updateNew($hotNovel);
                    if($return['code'])
                        $this->info("小说[{$hotNovel->id}]：{$hotNovel->name}更新成功");
                    else
                        $this->info("小说[{$hotNovel->id}]：{$hotNovel->name}更新失败");
                }
                $this->info('----- FINISHED THE PROCESS FOR UPDATE OF HOT LIMIT '.$number.' -----');
            }
        } catch (ModelNotFoundException $e) {
            Log::error($e);
            $this->error('They received errors when running the process. View Log File.');
        }
    }
}
