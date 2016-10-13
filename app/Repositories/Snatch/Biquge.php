<?php

/**
 * This file is part of Novel
 * (c) Maple <copyrenzhe@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\Snatch;

use Carbon\Carbon;
use App\Models\Author;
use App\Models\Chapter;
use App\Models\Novel;
use Log;

/**
 * Class Biquge
 * @package App\Repositories\Snatch
 */
Class Biquge implements SnatchInterface
{
    const COOKIE = './biquge.cookie';
    const USERAGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36';
    const REFERER = 'http://www.biquge.la';
    const DOMAIN = 'http://www.biquge.la';

    private $page_size = 200;

    /**
     * 初始化小说列表，获取当前笔趣阁所有小说
     * @param $link
     * @return Novel|bool
     */
    public static function init($link)
    {
        $Biquge = new Biquge();
        if(!$link)
            return $Biquge->getNovelList();
        else
            return $Biquge->getSingleNovel($link);
    }

    /**
     * @desc 修复未获取到内容的章节，若传入小说id，则修复该小说的章节，否则修复所有内容为空的章节
     * @param Novel $novel
     * @param bool $force
     * @return bool
     */
    public static function repair(Novel $novel, $force=false )
    {
        $Biquge = new Biquge();
        return $Biquge->repairNovel($novel, $force);
    }

    public static function repairChapter(Chapter $chapter, $force = false)
    {
        $Biquge = new Biquge();
        return $Biquge->updateChapter($chapter, $force);
    }

    /**
     * 更新小说章节，当执行updateNew内存溢出时，执行此方法代替
     * @param Novel $novel
     * @return array [type] [description]
     */
    public static function update( Novel $novel)
    {
        $Biquge = new Biquge();
        return $Biquge->getNovelChapter($novel);
    }

    /**
     * @desc 使用curl_multi 多线程更新章节，可用于采集
     * @param Novel $novel
     * @return string|void
     */
    public static function updateNew(Novel $novel)
    {
        $Biquge = new Biquge();
        return $Biquge->getChapterNew($novel);
    }

    /**
     * 采集小说章节，不需考虑更新问题
     * @param Novel $novel
     * @return string|void
     */
    public static function snatch(Novel $novel)
    {
        $Biquge = new Biquge();
        return $Biquge->snatchChapter($novel);
    }

    /**
     * @param Novel $novel
     * @param $force
     * @return bool
     */
    public function repairNovel(Novel $novel, $force)
    {
        Log::info("开始修复");
        if(!!$novel){
            $force ?
                $url_list = Chapter::where('novel_id', $novel->id)
                            ->whereNotNull('biquge_url')
                            ->pluck('biquge_url')
                            ->toArray()
                :
                $url_list = Chapter::where('novel_id', $novel->id)
                    ->whereNotNull('biquge_url')
                    ->whereNull('content')
                    ->pluck('biquge_url')
                    ->toArray();
        }
        else{
            //需要确认修复的小说编号
            return false;
        }

        $countList = count($url_list);
        $num = ceil($countList/$this->page_size);
        Log::info("修复小说[{$novel->id}], 共[{$countList}]条章节需要修复,设定[{$num}]次循环");
        for ($i=0; $i<$num; $i++) {
            $start = $i * $this->page_size;
            Log::info("修复小说[{$novel->id}], 第[{$i}]次循环开始，从第[{$start}]条取[{$this->page_size}]条");
            $splice_list = array_slice($url_list, $start, $this->page_size);
            $contents = $this->multi_send_test($splice_list, '');
            $temp = [];
            foreach ($contents as $k => $html) {
                preg_match('/var readid = "(.*?)"/s', $html, $read_match);
                if(@$read_match[1]){
                    $biquge_id = $read_match[1];
                    $content = $this->getChapterContent($html);
                    $temp[$biquge_id] = $content;
                }
            }

            $value_array = [];
            foreach ($contents as $k => $content) {
                $linkArr = explode('/', $url_list[$i * $this->page_size + $k]);
                $idArr = explode('.', end($linkArr));
                $biquge_id = $idArr[0];
                $value_array[] = [
                    'biquge_url' => $url_list[$i * $this->page_size + $k],
                    'content' => @$temp[$biquge_id]
                ];
            }
            updateBatch('chapter', $value_array);
            Log::info("修复小说[{$novel->id}], 第[{$i}]次循环结束");
        }

        return true;
    }

    /**
     * @param Chapter $chapter
     * @param $force
     * @return bool
     */
    public function updateChapter(Chapter $chapter, $force)
    {
        if($chapter->biquge_url && (!$chapter->content || ($chapter->content && $force))){
            $html = $this->send($chapter->biquge_url);
            $content = $this->getChapterContent($html);
            $chapter->content = $content;
            return $chapter->save();
        }
        return false;
    }
    
    /**
     * 获取小说列表
     */
    public function getNovelList()
    {
        $list_url = self::DOMAIN . '/xiaoshuodaquan/';
        $result_html = $this->send($list_url);
        $novelList = $this->getDivList($result_html);
        if(!$novelList){
            Log::error('获取小说列表失败');
            die;
        }
        foreach($novelList as $novel){
            $type_name = $this->getDivType($novel);
            $type = $this->returnType($type_name);
            $info_arr = $this->getLiNovel($novel);
            if(!$info_arr[1]){
                Log::error('正则匹配小说名失败');
                die;
            }
            foreach($info_arr[1] as $key => $info){
                $novel_link = self::DOMAIN . $info;
                if(Novel::where('biquge_url', '=', $novel_link)->first()){
                    continue;
                }
                $novel_name = $info_arr[2][$key];
                $novel_is_over = $info_arr[3][$key] == '载' ? 0 : 1;
                $novel_author = $info_arr[4][$key];
                $author = Author::firstOrCreate(['name'=>$novel_author]);
                $novel = Novel::firstOrCreate(['name'=>$novel_name, 'author_id'=>$author->id]);
                $novel->type = $type;
                $novel->is_over = $novel_is_over;
                $novel->biquge_url = $novel_link;
                $novel_html = $this->send($novel_link);
                $novel->description = $this->getNovelInfo($novel_html);
                $cover_link = $this->getNovelCover($novel_html);
                $novel->chapter_num = 0;
                if(!empty($cover_link)) {
                    $cover_ext = substr($cover_link, strrpos($cover_link, '.')+1);
                    $path = public_path('cover/'.$novel->id.'_cover.'.$cover_ext);
                    //文件不存在时才获取图片
                    if(!file_exists($path)) {
                        $cover = file_get_contents($cover_link);
                        file_put_contents($path, $cover);
                    }
                    $novel->cover = '/cover/'.$novel->id.'_cover.'.$cover_ext;
                }
                $novel->save();
            }
        }
        return true;
    }

    /**
     * 根据链接初始化单个小说
     * @param $link 小说网址
     * @return Novel $novel 返回小说实例
     */
    public function getSingleNovel($link)
    {
        $novel_html = $this->send($link);
        if(preg_match('/property="og:novel:book_name" content="(.*?)"/s', $novel_html, $novel_name)){
            preg_match('/property="og:novel:author" content="(.*?)"/s', $novel_html, $novel_author);
            preg_match('/property="og:novel:category" content="(.*?)"/s', $novel_html, $category);
            preg_match('/property="og:novel:status" content="(.*?)"/s', $novel_html, $overMatch);
            $author = Author::firstOrCreate(['name'=>$novel_author[1]]);
            if(!$novel = Novel::where('name', $novel_name[1])->where('author_id', $author->id)->first()){
                $novel = Novel::firstOrCreate(['name'=>$novel_name[1], 'author_id'=>$author->id]);
                $novel->biquge_url = $link;
                if(@$overMatch[1]=='连载中'){
                    $novel->is_over = 0;
                }
                if(@$overMatch[1]=='完结'){
                    $novel->is_over = 1;
                }
                $novel->type = $this->returnType($category[1]);
                $novel->description = $this->getNovelInfo($novel_html);
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
        return true;
    }

    /**
     * @desc 使用curl_multi 多线程更新章节
     * @param Novel $novel
     * @return string|void
     */
    public function getChapterNew(Novel $novel)
    {
        $novel_html = $this->send($novel->biquge_url);

        $chapter_list = $this->getChapterList($novel_html);
        if(!$chapter_list[1]) {
            Log::error("小说[$novel->id]:[$novel->name]，获取章节列表失败，请注意查看");
            return ['code' => 0];
        }
        $count= $novel->chapter_num;
        if(count($chapter_list[1]) <= $count) {
            //小说未更新
            Log::error("小说[$novel->id]:[$novel->name]未更新");
            return ['code' => 1];
        }

        //目前数据库中的最新一章
        $last_novel = $novel->chapter()->orderBy('id', 'desc')->first();
        if($last_novel) {
            $last_url = $last_novel->biquge_url;
            $urlArr = explode('/', $last_url);
            $curr_key = array_search($urlArr[count($urlArr)-1], $chapter_list[1]);
        } else {
            $curr_key = -1;
        }

        $filter_list = [];
        $filter_list[1] = array_slice($chapter_list[1], $curr_key+1);
        $filter_list[2] = array_slice($chapter_list[2], $curr_key+1);

        $contents = $this->multi_send_test($filter_list[1], $novel->biquge_url, count($filter_list[1]));
        $temp = [];
        foreach ($contents as $k => $html) {
            preg_match('/var readid = "(.*?)"/s', $html, $read_match);
            if(@$read_match[1]){
                $biquge_id = $read_match[1];
                $content = $this->getChapterContent($html);
                $temp[$biquge_id] = $content;
            }
        }

        $value_array = [];
        $now = Carbon::now();
        foreach($filter_list[2] as $k => $name) {
            $biquge_idArr = explode('.', $filter_list[1][$k]);
            $biquge_id = $biquge_idArr[0];
            $link = $novel->biquge_url . $filter_list[1][$k];
            $value_array[] = [
                'biquge_url' => $link,
                'name' => $name,
                'content' => @$temp[$biquge_id],
                'novel_id' => $novel->id,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        unset($contents);
        Chapter::insert($value_array);

        //更新小说状态
        preg_match('/property="og:novel:status" content="(.*?)"/s', $novel_html, $overMatch);
        if(@$overMatch[1]=='连载中'){
            $novel->is_over = 0;
        }
        if(@$overMatch[1]=='完结'){
            $novel->is_over = 1;
        }
        $novel->chapter_num = count($chapter_list[1]);
        $novel->save();
        return true;
    }


    /**
     * 采集小说章节实现方法
     * @param Novel $novel
     * @return array
     */
    public function snatchChapter(Novel $novel)
    {
        $novel_html = $this->send($novel->biquge_url);
        $chapter_list = $this->getChapterList($novel_html);
        if(!$chapter_list[1]) {
            Log::error('getChapterList failed');
            return ['code' => 0];
        }

        $num = ceil(count($chapter_list[1])/$this->page_size);

        for ($i=0; $i<$num; $i++)
        {
            $splice_list = [];
            $splice_list[1] = array_slice($chapter_list[1], $i*$this->page_size, $this->page_size);
            $splice_list[2] = array_slice($chapter_list[2], $i*$this->page_size, $this->page_size);
            $contents = $this->multi_send_test($splice_list[1], $novel->biquge_url);
            $temp = [];
            foreach ($contents as $k => $html) {
                preg_match('/var readid = "(.*?)"/s', $html, $read_match);
                if(@$read_match[1]){
                    $biquge_id = $read_match[1];
                    $content = $this->getChapterContent($html);
                    $temp[$biquge_id] = $content;
                }
            }
            $value_array = [];
            $now = Carbon::now();
            foreach($splice_list[2] as $k => $name) {
                $idArr = explode('.', $splice_list[1][$k]);
                $biquge_id = $idArr[0];
                $value_array[] = [
                    'biquge_url' => $novel->biquge_url . $splice_list[1][$k],
                    'name' => $name,
                    'content' => @$temp[$biquge_id],
                    'novel_id' => $novel->id,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            unset($contents);
            Chapter::insert($value_array);
        }

        return ['code' => 1];
    }

    /**
     * 获取小说章节
     * @param Novel $novel
     * @return array
     */
    public function getNovelChapter( Novel $novel ) {
        $novel_html = $this->send($novel->biquge_url);
        $chapter_list = $this->getChapterList($novel_html);
        if(!$chapter_list[1]) {
            Log::error("小说[$novel->id]:[$novel->name]，获取章节列表失败，请注意查看");
            return ['code' => 0];
        }
        $count= $novel->chapter_num;
        if(count($chapter_list[1]) <= $count) {
            //小说未更新
            return ['code' => 1];
        }

        //目前数据库中的最新一章
        $last_url = $novel->chapter()->orderBy('id', 'desc')->first()->biquge_url;
        $urlArr = explode('/', $last_url);
        $curr_key = array_search($urlArr[count($urlArr)-1], $chapter_list[1]);

        $filter_list = [];
        $filter_list[1] = array_slice($chapter_list[1], $curr_key+1);
        $filter_list[2] = array_slice($chapter_list[2], $curr_key+1);

        $contents = $this->multi_send_test($filter_list[1], $novel->biquge_url, count($filter_list[1])); //该contents是无序的

        $temp = [];
        foreach ($contents as $k => $html) {
            $name = $this->getChapterName($html);
            $content = $this->getChapterContent($html);
            $temp[$name] = $content;
        }
        $now = Carbon::now();
        foreach($filter_list[2] as $k => $name) {
            $value_array = [
                'biquge_url' => $novel->biquge_url . $filter_list[1][$k],
                'name' => $name,
                'content' => @$temp[$name],
                'novel_id' => $novel->id,
                'created_at' => $now,
                'updated_at' => $now
            ];
            Chapter::create($value_array);
        }
    }

    private function getDivList($html)
    {
        $preg = '/<div class="novellist">(.*?)<\/div>/s';
        preg_match_all($preg, $html, $matches);
        return $matches[1];
    }

    private function getDivType($html)
    {
        preg_match('/<h2>(.*?)<\/h2>/s', $html, $match);
        return $match[1];
    }

    private function returnType($name)
    {
        if(preg_match('/玄幻小说/s', $name, $match)){
            return 'xuanhuan';
        }
        if(preg_match('/修真小说/s', $name, $match)){
            return 'xiuzhen';
        }
        if(preg_match('/都市小说/s', $name, $match)){
            return 'dushi';
        }
        if(preg_match('/历史小说/s', $name, $match)){
            return 'lishi';
        }
        if(preg_match('/网游小说/s', $name, $match)){
            return 'wangyou';
        }
        if(preg_match('/科幻小说/s', $name, $match)){
            return 'kehuan';
        }
        return 'other';
    }

    /**
     * 正则匹配小说名
     * @param $html
     * @return mixed
     */
    private function getLiNovel($html)
    {
        $preg = '/<li><a href="(.*?)" target="_blank">(.*?)<\/a>\((.*?)\) \/(.*?)<\/li>/s';
        preg_match_all($preg, $html, $matches);
        return $matches;
    }


    /**
     * 正则匹配小说描述
     * @param $html
     * @return mixed
     */
    private function getNovelInfo($html)
    {
        preg_match('/<div id="intro">(.*?)<\/div>/s', $html, $match);
        return @$match[1];
    }


    /**
     * 正则匹配小说图片
     * @param $html
     * @return mixed
     */
    private function getNovelCover($html)
    {
        $preg = '/<div id="fmimg"><img alt=".*?" src="(.*?)" width="120" height="150" \/><span class="b"><\/span><\/div>/';
        preg_match($preg, $html, $match);
        return @$match[1];
    }

    /**
     * 正则匹配章节列表
     * @param $html
     * @return mixed
     */
    private function getChapterList($html)
    {
        $preg = '/<dd><a href="(.*?)">(.*?)<\/a><\/dd>/s';
        preg_match_all($preg, $html, $matches);
        return $matches;
    }


    /**
     * 正则匹配章节内容
     * @param $html
     * @return mixed
     */
    private function getChapterContent($html)
    {
        $preg = '/<div id="content"><script>(.*?)<\/script>(.*?)<\/div>/s';
        preg_match($preg, $html, $content);
        if(!isset($content[2])){
//            Log::error("get Chapter Content fail");
        }
        return @$content[2];
    }

    /**
     * 正则匹配章节标题
     * @param $html
     * @return mixed
     */
    private function getChapterName($html)
    {
        $preg = '/<div class="bookname">.*?<h1>(.*?)<\/h1>/s';
        preg_match($preg, $html, $content);
        return @$content[1];
    }


    /**
     * 单线程模拟请求
     * @param $url
     * @param string $type
     * @param bool $params
     * @param string $encoding
     * @return mixed|string
     */
    private function send($url, $type = 'GET', $params = false, $encoding = 'gbk')
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        $html = curl_exec($ch);
        if($html === false) {
            echo "curl error: " . curl_errno($ch);
        }
        curl_close($ch);
        return mb_convert_encoding($html, 'UTF-8', $encoding);
    }


    /**
     * 多线程模拟请求
     * @param $url_array
     * @return array
     */
    private function multi_send($url_array)
    {
        return remote($url_array, 'GET', false, 'gbk', self::REFERER, self::COOKIE);
    }

    private function multi_send_test($url_array, $append_url, $page_count=0)
    {
        return async_get_url($url_array, $append_url, $page_count ? $page_count : $this->page_size);
    }
}