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
use Illuminate\Support\Facades\Log;

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

    private $page_size = 500;

    /**
     * 初始化小说列表，获取当前笔趣阁所有小说
     */
    public static function init()
    {
        $Biquge = new Biquge();
        return $Biquge->getNovelList();
    }

    /**
     * @desc 修复未获取到内容的章节，若传入小说id，则修复该小说的章节，否则修复所有内容为空的章节
     * @param int|null $novel_id
     */
    public static function repair($novel_id=0 )
    {
        $Biquge = new Biquge();
        if(!!$novel_id)
            $url_list = Chapter::where('biquge_url', '<>', '')->where('novel_id', $novel_id)->whereNull('content')->pluck('biquge_url')->toArray();
        else
            $url_list = Chapter::where('biquge_url', '<>', '')->whereNull('content')->pluck('biquge_url')->toArray();

        $contents = $Biquge->multi_send($url_list);
        foreach ($contents as $k => $content) {
            $value_array = [
                'content' => $Biquge->getChapterContent($content)
            ];
            Chapter::updateOrCreate([ 'biquge_url' => $url_list[$k] ], $value_array);
        }
    }

    /**
     * 更新小说章节
     * @param Novel $novel
     * @return array [type] [description]
     */
    public static function update( Novel $novel)
    {
        $Biquge = new Biquge();
        return $Biquge->getNovelChapter($novel);
    }

    /**
     * @desc 使用curl_multi 多线程更新章节
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
        $count= $novel->chapter()->count();
        if(count($chapter_list[1]) <= $count) {
            //小说未更新
            return ['code' => 1];
        }

        $filter_list = [];
        $chapter_list[1] = array_reverse($chapter_list[1]);
        $chapter_list[2] = array_reverse($chapter_list[2]);

        foreach ($chapter_list[1] as $key => $value) {
            if(!Chapter::where('biquge_url', $novel->biquge_url . $value)->first()) {
                $filter_list[1][] = $value;
                $filter_list[2][] = $chapter_list[2][$key];
            } else {
                break;
            }
        }
        if(count($filter_list[1]) == 0){
            Log::error("更新小说[$novel->id]:[$novel->name]失败，注意查看");
            return ;
        }
        $contents = $this->multi_send_test($filter_list[1], $novel->biquge_url, count($filter_list[1]));
        $value_array = array();
        $now = Carbon::now();
        foreach($contents as $k => $content) {
            $value_array[] = [
                'biquge_url' => $novel->biquge_url . $filter_list[1][$k],
                'name' => $filter_list[2][$k],
                'content' => $this->getChapterContent($content),
                'novel_id' => $novel->id,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        unset($contents);
        Chapter::insert($value_array);
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
            $value_array = array();
            $now = Carbon::now();
            foreach($contents as $k => $content) {
                $value_array[] = [
                    'biquge_url' => $novel->biquge_url . $splice_list[1][$k],
                    'name' => $splice_list[2][$k],
                    'content' => $this->getChapterContent($content),
                    'novel_id' => $novel->id,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            unset($contents);
            Chapter::insert($value_array);
        }

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
                return "getChapterList failed:\n";
                var_dump($novel_html);
                die;
            }
            $count= $novel->chapter()->where('content', '<>', '')->count();
            if(count($chapter_list[1]) <= $count) {
                return ;
            }
            foreach($chapter_list[1] as $k => $chapter_data) {
                if($k<$count){
                    continue;
                }
                $chapter_link = $chapter_data;
                $chapter_name = $chapter_list[2][$k];
                $chapter = Chapter::firstOrCreate(['name'=>$chapter_name, 'novel_id'=>$novel->id]);
                if(!$chapter->content) {
                    $chapter_html = $this->send($novel->biquge_url. $chapter_link);
                    $chapter->content = $this->getChapterContent($chapter_html);
                    $chapter->save();
                }
            }
            return $chapter_list[1]-$count;
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
        $type = 'other';
        switch ($name){
            case '玄幻小说列表':
                $type = 'xuanhuan';
                break;
            case '修真小说列表':
                $type = 'xiuzhen';
                break;
            case '都市小说列表':
                $type = 'dushi';
                break;
            case '历史小说列表':
                $type = 'lishi';
                break;
            case '网游小说列表':
                $type = 'wangyou';
                break;
            case '科幻小说列表':
                $type = 'kehuan';
                break;
        }
        return $type;
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
            Log::error("html:\n".$html."\ncontent:\n");
            Log::error($content);
        }
        return @$content[2];
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
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, $encoding);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, self::COOKIE);
        curl_setopt($ch, CURLOPT_COOKIEJAR, self::COOKIE);
        if($type == 'POST'){
            curl_setopt($ch, CURLOPT_PORT, 1);
        }
        if(!empty($params) && is_array($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_REFERER, self::REFERER);
        } else {
            curl_setopt($ch, CURLOPT_REFERER, self::REFERER);
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USERAGENT);
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

    private function multi_send_test($url_array, $append_url)
    {
        return async_get_url($url_array, $append_url, $this->page_size);
    }
}