<?php

namespace App\Console\Commands;

use App\Jobs\CompareQidanNovel;
use Illuminate\Console\Command;

class CompareQidianNovel extends Command
{
    /**
     * The name and signature of the console command.
     * @param mod {click:点击榜, recom:推荐, fin:完本榜}
     * @param type {1:周榜, 2:月榜, 3:总榜}
     * @param category {-1: 全部分类, 21: 玄幻, 1: 奇幻, 2:武侠, 22:仙侠, 4:都市, 5:历史, 9:科幻}
     * @var string
     */
    protected $signature = 'compare:qidian
                            {mod? :  模式}
                            {type? :  榜类别}
                            {category? : 分类}
                            {--queue : 是否进入队列}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) Snatch qidian rank to compare the novels in database';

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
        $queue = $this->option('queue');
        $mod = $this->argument('mod') ? $this->argument('mod') : 'click';
        $type = $this->argument('type') ? $this->argument('type') : '1';
        $category = $this->argument('category') ? $this->argument('category') : '-1';
        if($queue){
            dispatch( new CompareQidanNovel($mod, $type, $category));
        } else {
            $compare = new CompareQidanNovel($mod, $type, $category);
            $compare->handle();
        }
    }
}
