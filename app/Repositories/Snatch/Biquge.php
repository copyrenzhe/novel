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

    /**
     * 初始化小说列表，获取当前笔趣阁所有小说
     */
    public static function init()
    {
        $Biquge = new Biquge();
        return $Biquge->getNovelList();
    }

    public static function test()
    {
        $Biquge = new Biquge();
        $content = $Biquge->send('http://www.biquge.la/book/110/87768.html');
        return $Biquge->getChapterContent($content);
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

    public static function updateNew( Novel $novel)
    {
        $Biquge = new Biquge();
        return $Biquge->getChapterNew($novel);
    }

    public function getNovelList()
    {
        $list_url = self::DOMAIN . '/xiaoshuodaquan/';
        $result_html = $this->send($list_url);
        $novelList = $this->getDivList($result_html);
        if(!$novelList){
            var_dump($result_html);
            die;
        }
        foreach($novelList as $novel){
            $type_name = $this->getDivType($novel);
            $type = $this->returnType($type_name);
            $info_arr = $this->getLiNovel($novel);
            if(!$info_arr[1]){
                var_dump($novel);
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
                if(!empty($cover_link)){
                    $cover_ext = substr($cover_link, strrpos($cover_link, '.')+1);
                    $cover = file_get_contents($cover_link);
                    file_put_contents(public_path('cover/'.$novel->id.'_cover.'.$cover_ext), $cover);
                    $novel->cover = '/cover/'.$novel->id.'_cover.'.$cover_ext;
                }
                $novel->save();
            }
        }
    }


    /**
     * @param Novel $novel
     * @return string|void
     */
    public function getChapterNew(Novel $novel)
    {
        $novel_html = $this->send($novel->biquge_url);
        $chapter_list = $this->getChapterList($novel_html);
        if(!$chapter_list[1]) {
            return "getChapterList failed:\n";
            Log::error('getChapterList failed');
            die;
        }
        $count= $novel->chapter()->where('content', '<>', '')->count();
        if(count($chapter_list[1]) <= $count) {
            return ;
        }
        foreach ($chapter_list[1] as $k => $chapter_data) {
            $chapter_list[1][$k] = $novel->biquge_url . $chapter_data;
        }
        $contents = $this->multi_send($chapter_list[1]);
        foreach($contents as $k => $content) {
//            $attr_array = ['biquge_url' => $chapter_list[1][$k]];
            $value_array = [
                'name' => $chapter_list[2][$k],
                'content' => $this->getChapterContent($content),
                'novel_id' => $novel->id
            ];
            Chapter::updateOrCreate(['biquge_url' => $chapter_list[1][$k]], $value_array);
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

    private function getLiNovel($html)
    {
        $preg = '/<li><a href="(.*?)" target="_blank">(.*?)<\/a>\((.*?)\) \/(.*?)<\/li>/s';
        preg_match_all($preg, $html, $matches);
        return $matches;
    }

    private function getNovelInfo($html)
    {
        preg_match('/<div id="intro">(.*?)<\/div>/s', $html, $match);
        return @$match[1];
    }

    private function getNovelCover($html)
    {
        $preg = '/<div id="fmimg"><img alt=".*?" src="(.*?)" width="120" height="150" \/><span class="b"><\/span><\/div>/';
        preg_match($preg, $html, $match);
        return @$match[1];
    }

    private function getChapterList($html)
    {
        $preg = '/<dd><a href="(.*?)">(.*?)<\/a><\/dd>/s';
        preg_match_all($preg, $html, $matches);
        return $matches;
    }

    private function getChapterContent($html)
    {
        $preg = '/<div id="content"><script>(.*?)<\/script>(.*?)<\/div>/s';
        preg_match($preg, $html, $content);
        if(!isset($content[2])){
            \Log::error("html:\n".$html."\ncontent:\n");
            \Log::error($content);
        }
        return @$content[2];
    }

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

    private function multi_send($url_array)
    {
//        return remote($url_array, 'GET', false, 'gbk', self::REFERER, self::COOKIE);
        $contents = array();
        $len = count($url_array);
        $max_size = $len;
        $requestMap = array();

        $mh = curl_multi_init();
        for ($i = 0; $i < $max_size; $i++)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $url_array[$i]);
            curl_setopt($ch, CURLOPT_COOKIE, self::COOKIE);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $requestMap[$i] = $ch;
            curl_multi_add_handle($mh, $ch);
        }

        do {
            while (($cme = curl_multi_exec($mh, $active)) == CURLM_CALL_MULTI_PERFORM);

            if ($cme != CURLM_OK) {break;}

            while ($done = curl_multi_info_read($mh))
            {
                $info = curl_getinfo($done['handle']);
                $tmp_result = curl_multi_getcontent($done['handle']);
                $error = curl_errno($done['handle']);

                $contents[] = $error == 0 ? mb_convert_encoding($tmp_result, 'UTF-8', 'gbk') : '';;

                //保证同时有$max_size个请求在处理
//                if ($i < sizeof($url_array) && isset($url_array[$i]) && $i < count($url_array))
//                {
//                    $ch = curl_init();
//                    curl_setopt($ch, CURLOPT_HEADER, 0);
//                    curl_setopt($ch, CURLOPT_URL, $url_array[$i]);
//                    curl_setopt($ch, CURLOPT_COOKIE, self::COOKIE);
//                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36');
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//                    $requestMap[$i] = $ch;
//                    curl_multi_add_handle($mh, $ch);
//                    $i++;
//                }
                curl_multi_remove_handle($mh, $done['handle']);
            }
            if ($active)
                curl_multi_select($mh, 10);
        } while ($active);

        curl_multi_close($mh);
        return $contents;
    }
}