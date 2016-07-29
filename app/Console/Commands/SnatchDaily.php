<?php

namespace App\Console\Commands;

use Log;
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
    protected $signature = 'snatch:updateAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) This command is used to update all novel`s chapters.';

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
            $novels = Novel::all()->get();
            if($novels) {
                $this->info('All novels to be processed');
                foreach ($novels as $novel) {
                    $return = Biquge::updateNew($novel);
                    if($return['code'])
                        $this->info("小说[{$novel->id}]：{$novel->name}更新成功");
                    else
                        $this->info("小说[{$novel->id}]：{$novel->name}更新失败");
                    Biquge::repair($novel->id);
                }
                $this->info('----- FINISHED THE PROCESS FOR UPDATE ALL NOVELS -----');
            }
        } catch (ModelNotFoundException $e) {
            Log::error($e);
            $this->error('They received errors when running the process. View Log File.');
        }
    }
}
