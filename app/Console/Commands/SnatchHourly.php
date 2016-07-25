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
    protected $signature = 'snatch:updatehot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) This command is used to update hot novel`s chapters.';

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
        //
        try{
            $this->info('----- STARTING THE PROCESS FOR UPDATE OF HOT LIMIT 30 -----');
            $hotNovels = Novel::continued()->orderBy('hot', 'desc')->take(30)->get();
            if($hotNovels) {
                $this->info('Hot novels to be processed');
                foreach ($hotNovels as $hotNovel) {
                    $return = Biquge::update($hotNovel);
                    if($return['code'])
                        $this->info("小说[{$hotNovel->id}]：{$hotNovel->name}更新成功，共更新{$return['info']}章");
                    else
                        $this->info("小说[{$hotNovel->id}]：{$hotNovel->name}更新失败，原因：{$return['info']}");
                }
                $this->info('----- FINISHED THE PROCESS FOR UPDATE OF HOT LIMIT 30 -----');
            }
        } catch (ModelNotFoundException $e) {
            Log::error($e);
            $this->error('They received errors when running the process. View Log File.');
        }
    }
}
