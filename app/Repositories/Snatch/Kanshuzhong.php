<?php

/**
 * This file is part of Novel
 * (c) Maple <copyrenzhe@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\Snatch;

use App\Models\Author;
use App\Models\Chapter;
use App\Models\Novel;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Database\QueryException;
use Log;

class Kanshuzhong extends Snatch implements SnatchInterface
{
    const COOKIE = './kanshuzhong.cookie';
    const USERAGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36';
    const REFERER = 'http://www.kanshuzhong.com';
    const DOMAIN = 'http://www.kanshuzhong.com';

    private $source = 'kanshuzhong';

    public function getNovelList()
    {
        $xuanhuan_link = self::DOMAIN . '/1.html';
        $list_html = $this->send($xuanhuan_link);
        $novel_matches = $this->getLiNovel($list_html);
        return $novel_matches[1];
    }

    public function init($link)
    {
        $novel_html = $this->send($link);
        if(preg_match('/property="og:novel:book_name" content="(.*?)"/s', $novel_html, $novel_name)){
            preg_match('/property="og:novel:author" content="(.*?)"/s', $novel_html, $novel_author);
            preg_match('/property="og:novel:category" content="(.*?)"/s', $novel_html, $category);
            preg_match('/property="og:novel:status" content="(.*?)"/s', $novel_html, $overMatch);
            $author = Author::firstOrCreate(['name'=>$novel_author[1]]);
            if(!$novel = Novel::where('name', $novel_name[1])->where('author_id', $author->id)->first()){
                $novel = Novel::firstOrCreate(['name'=>$novel_name[1], 'author_id'=>$author->id]);
                $novel->source = $this->source;
                $novel->source_link = strstr($link, '/book');
                $novel->chapter_num = 0;
                if(@$overMatch[1]=='连载中'){
                    $novel->is_over = 0;
                } else {
                    $novel->is_over = 1;
                }

                $novel->type = $this->returnType($category[1]);
                preg_match('/property="og:description" content="(.*?)"/s', $novel_html, $descMatch);
                $novel->description = $descMatch[1];
                if(preg_match('/property="og:image" content="(.*?)"/s', $novel_html, $image)) {
                    $cover_ext = substr($image[1], strrpos($image[1], '.')+1);
                    $path = public_path('cover/'.$novel->id.'_cover.'.$cover_ext);
                    //文件不存在时才获取图片
                    if(!file_exists($path)) {
                        $cover = file_get_contents($image[1]);
                        file_put_contents($path, $cover);
                    }
                    $novel->cover = '/cover/'.$novel->id.'_cover.'.$cover_ext;
                }
                $novel->save();
            }
            return $novel;
        }
        return false;
    }

    public function update(Novel $novel)
    {
        $novel_html = $this->send(self::DOMAIN . $novel->source_link);
        $chapter_list = $this->getChapterList($novel_html);
        if(!$chapter_list[1]) {
            Log::error("小说[$novel->id]:[$novel->name]，获取章节列表失败，请注意查看");
            return ['code' => 0];
        }
        $count = $novel->chapter_num;
        if(count($chapter_list[1]) <= $count) {
            //小说未更新
            Log::info("小说[$novel->id]:[$novel->name]未更新");
            return ['code' => 1];
        }
        Log::info("小说[$novel->id]正在更新，共有".(count($chapter_list[1])-$count)."章需要更新");
        $last_novel = $novel->chapter()->orderBy('id', 'desc')->first();
        if($last_novel) {
            $last_url = $last_novel->source_link;
            $urlArr = explode('/', $last_url);
            $curr_key = array_search(end($urlArr), $chapter_list[1]);
        } else {
            $curr_key = -1;
        }

        $filter_list = [];
        $filter_list[1] = array_slice($chapter_list[1], $curr_key+1);
        $filter_list[2] = array_slice($chapter_list[2], $curr_key+1);


        $contents = $this->multi_send_test($filter_list[1], self::DOMAIN . $novel->source_link, count($filter_list[1]));
        $temp = [];
        foreach ($contents as $k => $html) {
            preg_match('/<link rel="canonical" href="http:\/\/www\.kanshuzhong\.com\/book\/.*?\/(.*)?\.html" \/>/s', $html, $read_match);
            if(@$read_match[1]){
                $source_chapter_id = $read_match[1];
                $content = $this->getChapterContent($html);
                $temp[$source_chapter_id] = $content;
            }
        }

        $value_array = [];
        $now = Carbon::now();
        foreach($filter_list[2] as $k => $name) {
            $biquge_idArr = explode('.', $filter_list[1][$k]);
            $source_chapter_id = $biquge_idArr[0];
            $link = $filter_list[1][$k];
            $value_array[] = [
                'source_link' => self::DOMAIN . $novel->source_link . $link,
                'name' => $name,
                'content' => @$temp[$source_chapter_id],
                'novel_id' => $novel->id,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        unset($contents);
        try{
            Chapter::insert($value_array);
            $novel->chapter_num = count($chapter_list[1]);
        } catch (QueryException $e) {
            Log::error("小说[$novel->id]批量插入失败，正在逐条插入");
            try{
                foreach ($value_array as $v) {
                    $chapter = Chapter::updateOrCreate(['source_link' => $v['source_link']], $v);
                    Log::info("小说[$novel->id]: 更新章节:[$chapter->id],来源：[$novel->source_link . $v->source_link]");
                }
                Log::info("小说[$novel->id]章节更新完毕");
                $novel->chapter_num = count($chapter_list[1]);

            } catch (ErrorException $e) {
                Log::error("小说[$novel->id]逐条插入也失败，正在重新获取该小说");
                Log::info("清空小说[$novel->id]所有章节，并重新获取");
                Chapter::where('novel_id', $novel->id)->delete();
                self::snatch($novel);
            }
        }
        Log::info("正在更新小说[$novel->id]状态");
        //更新小说状态
        preg_match('/property="og:novel:status" content="(.*?)"/s', $novel_html, $overMatch);
        if(@$overMatch[1]=='连载中'){
            $novel->is_over = 0;
        } else {
            $novel->is_over = 1;
        }
        $novel->save();
        Log::info("小说[$novel->id]状态更新完毕");
        return ['code' => 1];
    }

    public function snatch(Novel $novel)
    {
        return ;
    }

    private function getChapterList($html)
    {
        $preg = '/<dd>.*?<a href="(.*?)">(.*?)<\/a>.*?<\/dd>/is';
        preg_match_all($preg, $html, $matches);
        return $matches;
    }

    private function getChapterContent($html)
    {
        $preg = '/<div class="ad"><script>read_01\(\)\;<\/script><\/div>(.*?)<div class="ad"><script>read_02\(\)\;<\/script><\/div>/s';
        preg_match($preg, $html, $match);
        return @$match[1];
    }

    private function returnType($name)
    {
        switch ($name){
            case '科幻小说':
                return 'kehuan';
            case '玄幻奇幻':
                return 'xuanhuan';
            case '武侠修真':
                return 'xiuzhen';
            case '都市言情':
                return 'dushi';
            case '历史穿越':
                return 'lishi';
            case '网游小说':
                return 'wangyou';
            case '恐怖灵异':
                return 'kongbu';
            default:
                return 'other';
        }
    }

}