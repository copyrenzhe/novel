<?php

namespace App\Jobs;

use App\Events\MailPostEvent;
use App\Jobs\Job;
use App\Models\Novel;
use Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompareQidanNovel extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $mod;
    private $type;
    private $category;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mod, $type, $category)
    {
        $this->mod = $mod;
        $this->type = $type;
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nameArr = qidianRank($this->mod, $this->type, $this->category);
        $in_name = Novel::whereIn('name', $nameArr)->pluck('name')->toArray();
        $excep_name = array_where($nameArr, function($key, $value) use($in_name){
            return !in_array($value, $in_name);
        });
        $mod_str = '';
        switch ($this->mod){
            case 'click':
                $mod_str = '点击榜';
                break;
            case 'recom':
                $mod_str = '推荐榜';
                break;
            case 'fin':
                $mod_str = '完本榜';
                break;
            default:
                break;
        }
        $type_str = '';
        switch ($this->type){
            case '1':
                $type_str = '周';
                break;
            case '2':
                $type_str = '月';
                break;
            case '3':
                $type_str = '总';
                break;
            default:
                break;
        }
        $category_str = '';
        switch ($this->category){
            case '-1':
                $category_str = '全部分类';
                break;
            case '21':
                $category_str = '玄幻';
                break;
            case '1':
                $category_str = '奇幻';
                break;
            case '2':
                $category_str = '武侠';
                break;
            case '22':
                $category_str = '仙侠';
                break;
            case '4':
                $category_str = '都市';
                break;
            case '5':
                $category_str = '历史';
                break;
            case '9':
                $category_str = '科幻';
                break;
            default:
                break;
        }
        $title = '与起点小说排行榜('.$type_str.$category_str.$mod_str.')对比';
        Event::fire(new MailPostEvent('compare_qidian', $title, $excep_name));
    }
}
