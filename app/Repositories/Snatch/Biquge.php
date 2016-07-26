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
        $preg = '/<div id="content"><script>readx\(\);<\/script>(.*?)<\/div>/s';
        preg_match($preg, $html, $content);
        return $content[1];
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
        return remote($url_array, 'GET', false, 'gbk', self::REFERER, self::COOKIE);
    }
}