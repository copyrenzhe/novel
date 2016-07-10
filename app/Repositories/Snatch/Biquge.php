<?php

/**
 * This file is part of Novel
 * (c) Maple <copyrenzhe@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\Snatch;

use App\Models\Author;
use App\Models\Novel;
use PhpQuery\PhpQuery as phpQuery;

Class Biquge implements SnatchInterface
{
    const COOKIE = './biquge.cookie';
    const USERAGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36';
    const REFERER = 'http://www.biquge.la';
    const DOMAIN = 'http://www.biquge.la';

    private $type;

    public static function init()
    {
        $Biquge = new Biquge();
        $Biquge->type = '';
        return $Biquge->newNovelList();
    }

    public function newNovelList()
    {
        $list_url = self::DOMAIN . '/xiaoshuodaquan/';
//        $result_html = $this->send($list_url);
        $result_html = file_get_contents($list_url);
        var_dump($result_html);
        $novelList = $this->getDivList($result_html);
        foreach($novelList as $novel){
            $type_name = $this->getDivType($novel);
            $type = $this->returnType($type_name);
            var_dump($type);
//            $info_arr = $this->getLiNovel($novel);
//            foreach($info_arr[1] as $)
        }
    }

    private function getDivList($html)
    {
        $preg = '/<div class="novellist">(.*?)<\/div>/';
        preg_match_all($preg, $html, $matches);
        return $matches[1];
    }

    private function getDivType($html)
    {
        preg_match('/<h2>(.*?)<\/h2>', $html, $match);
        return $match[1];
    }

    private function returnType($name)
    {
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
        $preg = '/<li><a href="(.*?)"> target="_balnk">(.*?)<\/a>\((.*?)\) \/(.*?)<\/li>/';
        preg_match_all($preg, $html, $matches);
        return $matches;
    }

    public function getNovelList()
    {
        $list_url = self::DOMAIN . '/xiaoshuodaquan/';
        $result_html = $this->send($list_url);
        $res = phpQuery::newDocument($result_html);
        $novellist = phpQuery::pq($res)->find('.novellist');
        phpQuery::each($novellist, function ($item, $data) {
            $name = phpQuery::pq($data)->find('h2')->html();
            switch ($name){
                case '玄幻小说列表':
                    $this->type = 'xuanhuan';
                    break;
                case '修真小说列表':
                    $this->type = 'xiuzhen';
                    break;
                case '都市小说列表':
                    $this->type = 'dushi';
                    break;
                case '历史小说列表':
                    $this->type = 'lishi';
                    break;
                case '网游小说列表':
                    $this->type = 'wangyou';
                    break;
                case '科幻小说列表':
                    $this->type = 'kehuan';
                    break;
            }
            dd($name);
            $li_list = phpQuery::pq($data)->find('li');
            phpQuery::each($li_list, function ($index, $li) {
                $book_a = phpQuery::pq($li)->find('a');
                $book_name = $book_a->html();
                $book_link = $book_a->attr('href');
                $li_html = phpQuery::pq($li)->html();
                preg_match('/<a.*?>.*?<\/a>\((.*?)\).*?\/(.*?)$/i', $li_html, $match);
                $is_over = ($match[1] == '载') ? 0 : 1;
                $author = $match[2];
                $Mauthor = Author::firstOrCreate(['name'=>$author]);
                $novel = Novel::firstOrCreate(['name'=>$book_name, 'author_id'=>$Mauthor->id, 'type'=>$this->type, 'is_over'=>$is_over]);
//                $this->getChapter($book_link, $novel);
            });
        });

    }

    public function getChapter($link, $novel)
    {
        $url = self::DOMAIN . $link;
        $chapter_html = $this->send($url);
        $res = phpQuery::newDocument($chapter_html);
        $chapter_list = phpQuery::pq($res)->find('#list dd');
        phpQuery::each($chapter_list, function($index, $cap){
            $cap_a = phpQuery::pq($cap)->find('a');
            $cap_name = $cap_a->html();
            $cap_link = $cap_a->attr('href');
            $cap_detail = $this->send(self::DOMAIN . $cap_link);
            $result = phpQuery::newDocument($cap_detail);
            $cap_content = phpQuery::pq($result)->find('#content');
        });
    }

    private function send($url, $type = 'GET', $params = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
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
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USERAGENT);
        $html = curl_exec($ch);
        if($html === false) {
            echo "curl error: " . curl_errno($ch);
        }
        curl_close($ch);
        return $html;
    }
}