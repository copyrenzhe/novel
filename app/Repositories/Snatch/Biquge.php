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
use ErrorException;
use Illuminate\Database\QueryException;
use Log;
use Mockery\CountValidator\Exception;

/**
 * Class Biquge
 * @package App\Repositories\Snatch
 */
Class Biquge extends Snatch implements SnatchInterface
{
    const COOKIE = './biquge.cookie';
    const USERAGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36';
    const REFERER = 'http://www.qu.la';
    const DOMAIN = 'http://www.qu.la';

    protected $source = 'biquge';

    /**
     * @desc 修复未获取到内容的章节，若传入小说id，则修复该小说的章节，否则修复所有内容为空的章节
     * @param Novel $novel
     * @param bool $force
     * @return bool
     */
    public function repair(Novel $novel, $force)
    {
        Log::info("开始修复");
        if (!!$novel) {
            $force ?
                $url_list = Chapter::where('novel_id', $novel->id)
                    ->whereNotNull('source_link')
                    ->pluck('source_link')
                    ->toArray()
                :
                $url_list = Chapter::where('novel_id', $novel->id)
                    ->whereNotNull('source_link')
                    ->whereNull('content')
                    ->pluck('source_link')
                    ->toArray();
        } else {
            //需要确认修复的小说编号
            return false;
        }

        $countList = count($url_list);
        $num = ceil($countList / $this->page_size);
        Log::info("修复小说[{$novel->id}], 共[{$countList}]条章节需要修复,设定[{$num}]次循环");
        for ($i = 0; $i < $num; $i++) {
            $start = $i * $this->page_size;
            Log::info("修复小说[{$novel->id}], 第[{$i}]次循环开始，从第[{$start}]条取[{$this->page_size}]条");
            $splice_list = array_slice($url_list, $start, $this->page_size);
            $sourceLink = $novel->source_link;
            $repairUrl = array_map(function($v) use($sourceLink) {
                $chapterInfo = explode('/', $v);
                $chapterId = end($chapterInfo);
                return self::DOMAIN . $sourceLink . $chapterId;
            }, $splice_list);
            $contents = $this->multi_send_test($repairUrl, $this->page_size, 'utf-8');
            $temp = [];
            foreach ($contents as $k => $html) {
                preg_match('/addBookMark\((\d+),.*?\)/s', $html, $read_match);
                if (@$read_match[1]) {
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
                    'source_link' => self::DOMAIN . $novel->source_link . $url_list[$i * $this->page_size + $k],
                    'content' => @$temp[$biquge_id]
                ];
            }
            try {
                updateBatch('chapter', $value_array);
            } catch (QueryException $e) {
                Log::error("修复小说[{$novel->id}], 批量插入失败");
                foreach ($value_array as $value) {
                    Log::info("修复小说[{$novel->id}]，章节：{$value['source_link']}");
                    Chapter::updateOrCreate(['source_link' => $value['source_link']], ['content' => $value['content']]);
                }
            }
            Log::info("修复小说[{$novel->id}], 第[{$i}]次循环结束");
        }

        return true;
    }

    /**
     * @param Chapter $chapter
     * @param $force
     * @return bool
     */
    public function repairChapter(Chapter $chapter, $force)
    {
        if ($chapter->source_link && (!$chapter->content || ($chapter->content && $force))) {
            $html = $this->send($chapter->source_link, 'GET', false, 'utf-8');
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
        $result_html = $this->send($list_url, 'GET', false, 'utf-8');
        $novelList = $this->getDivList($result_html);
        if (!$novelList) {
            Log::error('获取小说列表失败');
            die;
        }
        foreach ($novelList as $novel) {
            $type_name = $this->getDivType($novel);
            $type = $this->returnType($type_name);
            $info_arr = $this->getLiNovel($novel);
            if (!$info_arr[1]) {
                Log::error('正则匹配小说名失败');
                die;
            }
            foreach ($info_arr[1] as $key => $info) {
                $novel_link = $info;
                if (Novel::where('source_link', '=', $novel_link)->first()) {
                    continue;
                }
                $novel_name = $info_arr[2][$key];
                $novel_is_over = $info_arr[3][$key] == '载' ? 0 : 1;
                $novel_author = $info_arr[4][$key];
                $author = Author::firstOrCreate(['name' => $novel_author]);
                $novel = Novel::firstOrCreate(['name' => $novel_name, 'author_id' => $author->id]);
                $novel->type = $type;
                $novel->is_over = $novel_is_over;
                $novel->source_link = $novel_link;
                $novel->source = $this->source;
                $novel_html = $this->send(self::DOMAIN . $novel_link, 'GET', false, 'utf-8');
                $novel->description = $this->getNovelInfo($novel_html);
                $cover_link = $this->getNovelCover($novel_html);
                $novel->chapter_num = 0;
                if (!empty($cover_link)) {
                    $cover_ext = substr($cover_link, strrpos($cover_link, '.') + 1);
                    $path = public_path('cover/' . $novel->id . '_cover.' . $cover_ext);
                    //文件不存在时才获取图片
                    if (!file_exists($path)) {
                        $cover = file_get_contents($cover_link);
                        file_put_contents($path, $cover);
                    }
                    $novel->cover = '/cover/' . $novel->id . '_cover.' . $cover_ext;
                }
                $novel->save();
            }
        }
        return true;
    }

    /**
     * 根据链接初始化单个小说
     * @param $link 小说网址
     * @return mixed Novel $novel 返回小说实例
     */
    public function init($link)
    {
        $novel_html = $this->send($link, 'GET', false, 'utf-8');
        if (!$novel_html) {
            return false;
        }
        if (preg_match('/property="og:novel:book_name" content="(.*?)"/s', $novel_html, $novel_name)) {
            preg_match('/property="og:novel:author" content="(.*?)"/s', $novel_html, $novel_author);
            preg_match('/property="og:novel:category" content="(.*?)"/s', $novel_html, $category);
            preg_match('/property="og:novel:status" content="(.*?)"/s', $novel_html, $overMatch);
            $author = Author::firstOrCreate(['name' => $novel_author[1]]);
            if (!$novel = Novel::where('name', $novel_name[1])->where('author_id', $author->id)->first()) {
                $novel = Novel::firstOrCreate(['name' => $novel_name[1], 'author_id' => $author->id]);
                $novel->source = $this->source;
                $novel->source_link = strstr($link, '/book');
                if (@$overMatch[1] == '连载中') {
                    $novel->is_over = 0;
                }
                if (@$overMatch[1] == '完结') {
                    $novel->is_over = 1;
                }
                $novel->type = $this->returnType($category[1]);
                $novel->description = $this->getNovelInfo($novel_html);
                if (preg_match('/property="og:image" content="(.*?)"/s', $novel_html, $image)) {
                    $cover_ext = substr($image[1], strrpos($image[1], '.') + 1);
                    $path = public_path('cover/' . $novel->id . '_cover.' . $cover_ext);
                    //文件不存在时才获取图片
                    if (!file_exists($path)) {
                        $cover = file_get_contents($image[1]);
                        file_put_contents($path, $cover);
                    }
                    $novel->cover = '/cover/' . $novel->id . '_cover.' . $cover_ext;
                }
                $novel->save();
            }
            return $novel;
        }
        return false;
    }

    /**
     * @desc 使用curl_multi 多线程更新章节
     * @param Novel $novel
     * @return string|mixed
     */
    public function update(Novel $novel)
    {
        $novel_html = $this->send(self::DOMAIN . $novel->source_link, 'GET', false, 'utf-8');
        if (!$novel_html) {
            return false;
        }

        $chapter_list = $this->getChapterList($novel_html);
        if (!$chapter_list[1]) {
            Log::error("小说[$novel->id]:[$novel->name]，获取章节列表失败，请注意查看");
            return ['code' => 0];
        }
        $count = $novel->chapter_num;
        if (count($chapter_list[1]) <= $count) {
            //小说未更新
            Log::info("小说[$novel->id]:[$novel->name]未更新");
            return ['code' => 1];
        }
        Log::info("小说[$novel->id]正在更新，共有" . (count($chapter_list[1]) - $count) . "章需要更新");
        //目前数据库中的最新一章
        $last_novel = $novel->chapter->last();
        if ($last_novel) {
            $last_url = $last_novel->source_link;
            $urlArr = parse_url($last_url);
            $curr_key = array_search($urlArr['path'], $chapter_list[1]);
        } else {
            $curr_key = -1;
        }

        $filter_list = [];
        $filter_list[1] = array_slice($chapter_list[1], $curr_key + 1);
        $filter_list[2] = array_slice($chapter_list[2], $curr_key + 1);

        $contents = $this->multi_send_test($filter_list[1], self::DOMAIN . $novel->source_link, count($filter_list[1]), 'utf-8');
        $temp = [];
        foreach ($contents as $k => $html) {
            preg_match('/addBookMark\((\d+),.*?\)/s', $html, $read_match);
            if (@$read_match[1]) {
                $biquge_id = $read_match[1];
                $content = $this->getChapterContent($html);
                $temp[$biquge_id] = $content;
            }
        }

        $value_array = [];
        $now = Carbon::now();
        foreach ($filter_list[2] as $k => $name) {
            $biquge_idArr = explode('.', $filter_list[1][$k]);
            $biquge_idChunk = explode('/', $biquge_idArr[0]);
            $biquge_id = end($biquge_idChunk);
            $link = $filter_list[1][$k];
            $value_array[] = [
                'source_link' => self::DOMAIN . $link,
                'name' => $name,
                'content' => @$temp[$biquge_id],
                'novel_id' => $novel->id,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        unset($contents);
        try {
            Chapter::insert($value_array);
            $novel->chapter_num = count($chapter_list[1]);
        } catch (QueryException $e) {
            Log::error("小说[$novel->id]批量插入失败，正在逐条插入");
            try {
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
                Biquge::snatch($novel);
            }
        }
        Log::info("正在更新小说[$novel->id]状态");
        //更新小说状态
        preg_match('/property="og:novel:status" content="(.*?)"/s', $novel_html, $overMatch);
        if (@$overMatch[1] == '连载中') {
            $novel->is_over = 0;
        }
        if (@$overMatch[1] == '完结') {
            $novel->is_over = 1;
        }
        $novel->save();
        Log::info("小说[$novel->id]状态更新完毕");
        return ['code' => 1];
    }


    /**
     * 采集小说章节实现方法
     * @param Novel $novel
     * @return array|mixed
     */
    public function snatch(Novel $novel)
    {
        $novel_html = $this->send(self::DOMAIN . $novel->source_link, 'GET', false, 'utf-8');
        if (!$novel_html) {
            return false;
        }
        $chapter_list = $this->getChapterList($novel_html);
        if (!$chapter_list[1]) {
            Log::error('getChapterList failed');
            return ['code' => 0];
        }

        $total_num = count($chapter_list[1]);
        $num = ceil($total_num / $this->page_size);

        for ($i = 0; $i < $num; $i++) {
            $splice_list = [];
            $splice_list[1] = array_slice($chapter_list[1], $i * $this->page_size, $this->page_size);
            $splice_list[2] = array_slice($chapter_list[2], $i * $this->page_size, $this->page_size);
            $contents = $this->multi_send_test($splice_list[1], self::DOMAIN . $novel->source_link, $this->page_size, 'utf-8');
            $temp = [];
            foreach ($contents as $k => $html) {
                preg_match('/addBookMark\((\d+),.*?\)/s', $html, $read_match);
                if (@$read_match[1]) {
                    $biquge_id = $read_match[1];
                    $content = $this->getChapterContent($html);
                    $temp[$biquge_id] = $content;
                }
            }
            $value_array = [];
            $now = Carbon::now();
            foreach ($splice_list[2] as $k => $name) {
                $biquge_idArr = explode('.', $splice_list[1][$k]);
                $biquge_idChunk = explode('/', $biquge_idArr[0]);
                $biquge_id = end($biquge_idChunk);
                $link = $splice_list[1][$k];
                $value_array[] = [
                    'source_link' => self::DOMAIN . $link,
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
        $novel->chapter_num = $total_num;
        $novel->save();
        return ['code' => 1];
    }

    /**
     * 单线程更新小说章节，当执行update内存溢出时，执行此方法代替
     * @param Novel $novel
     * @return array [type] [description]
     */
    public function update_single(Novel $novel)
    {
        $novel_html = $this->send(self::DOMAIN . $novel->source_link, 'GET', false, 'utf-8');
        $chapter_list = $this->getChapterList($novel_html);
        if (!$chapter_list[1]) {
            Log::error("小说[$novel->id]:[$novel->name]，获取章节列表失败，请注意查看");
            return ['code' => 0];
        }
        $count = $novel->chapter_num;
        if (count($chapter_list[1]) <= $count) {
            //小说未更新
            return ['code' => 1];
        }

        //目前数据库中的最新一章
        $last_url = $novel->chapter->last()->source_link;
        $urlArr = explode('/', $last_url);
        $curr_key = array_search(end($urlArr), $chapter_list[1]);

        $filter_list = [];
        $filter_list[1] = array_slice($chapter_list[1], $curr_key + 1);
        $filter_list[2] = array_slice($chapter_list[2], $curr_key + 1);

        $contents = $this->multi_send_test($filter_list[1], self::DOMAIN . $novel->source_link, count($filter_list[1]), 'utf-8'); //该contents是无序的

        $temp = [];
        foreach ($contents as $k => $html) {
            $name = $this->getChapterName($html);
            $content = $this->getChapterContent($html);
            $temp[$name] = $content;
        }
        $now = Carbon::now();
        foreach ($filter_list[2] as $k => $name) {
            $value_array = [
                'source_link' => self::DOMAIN . $novel->source_link . $filter_list[1][$k],
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
        if (preg_match('/玄幻小说/s', $name, $match)) {
            return 'xuanhuan';
        }
        if (preg_match('/修真小说/s', $name, $match)) {
            return 'xiuzhen';
        }
        if (preg_match('/都市小说/s', $name, $match)) {
            return 'dushi';
        }
        if (preg_match('/历史小说/s', $name, $match)) {
            return 'lishi';
        }
        if (preg_match('/网游小说/s', $name, $match)) {
            return 'wangyou';
        }
        if (preg_match('/科幻小说/s', $name, $match)) {
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
        $preg = '/<dd>.*?<a.*? href="(.*?)">(.*?)<\/a>.*?<\/dd>/s';
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
        $preg = '/<div id="content">\s+(.*?)\s+.*?\s+<\/div>/s';
        preg_match($preg, $html, $content);
        if (!isset($content[1])) {
            Log::error("get Chapter Content fail");
        }
        $result = preg_replace("/<script[^>]*?>.*?<\/script>/i", "", @$content[1]);
        return $result;
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

}