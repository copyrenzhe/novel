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
use Log;

class Mzhu extends Snatch implements SnatchInterface
{
    const COOKIE = './mzhu.cookie';
    const USERAGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36';
    const REFERER = 'http://www.mzhu8.com';
    const DOMAIN = 'http://www.mzhu8.com';

    protected $source = 'mzhu';

    public function getNovelList()
    {
        $list_url = [
            'http://www.mzhu8.com/mulu/6/1.html',   //国外名著
            'http://www.mzhu8.com/mulu/8/1.html',   //短篇名著
            'http://www.mzhu8.com/mulu/9/1.html',   //武侠名著
            'http://www.mzhu8.com/mulu/11/1.html',  //先秦文学
            'http://www.mzhu8.com/mulu/12/1.html',  //楚辞汉赋
            'http://www.mzhu8.com/mulu/13/1.html',  //魏晋文学
            'http://www.mzhu8.com/mulu/14/1.html',  //唐诗宋词
            'http://www.mzhu8.com/mulu/15/1.html',  //元朝文学
            'http://www.mzhu8.com/mulu/16/1.html',  //明清小说
            'http://www.mzhu8.com/mulu/17/1.html',  //现代文学
        ];
        $list_link = [];
        foreach ($list_url as $link){
            $links  = $this->getNovelListAction($link);
            $list_link = array_merge($list_link, $links);
        }
        return $list_link;
    }

    private function getNovelListAction($link)
    {
        $linkArr = explode('/', $link);
        $linkLast = count($linkArr)-1;
        $result_html = $this->send($link);
        //max page
        preg_match('/<a href=".*?" class="last">(.*?)<\/a>/s', $result_html, $max_page_match);
        $max_page = $max_page_match[1];
        $novelLinks = [];
        for ($i=1; $i<=$max_page; $i++){
            $linkArr[$linkLast] = $i.'.html';
            $page_link = implode('/', $linkArr);
            $page_html = $this->send($page_link);
            preg_match_all('/<div class="l_pic"><a href="(.*?)">.*?<\/a><\/div>/s', $page_html, $matches);
            $novelLinks = array_merge($novelLinks, $matches[1]);
        }
        return $novelLinks;
    }

    /**
     * 初始化小说
     * @param $link
     * @return Novel|bool
     */
    public function init($link)
    {
        $html = $this->send($link);
        if(preg_match('/<span class="i_author">作者：(.*?)  <\/span>/s', $html, $author_matches)){
            preg_match('/<h1>(.*?)<\/h1>/s', $html, $title_matches);
            preg_match('/<div id="fmimg"><img alt=".*?" src="(.*?)" \/><\/div>/s', $html, $img_matches);
            preg_match('/<div class="book_info">.*?<p>(.*?)<\/p>.*?<\/div>/s', $html, $info_matches);
            $name = $title_matches[1];
            $img_link = $img_matches[1];
            $description = $info_matches[1];
            $author = Author::firstOrCreate(['name'=>$author_matches[1]]);
            if(!$novel = Novel::where('name', $name)->where('author_id', $author->id)->first()){
                $novel = Novel::firstOrCreate(['name'=>$name, 'author_id'=>$author->id]);
                $novel->source = $this->source;
                $novel->source_link = strstr($link, '/book');
                $novel->is_over = 1;
                $novel->type = 'mingzhu';
                $novel->description = $description;
                if(getFileSize($img_link)==44110 || !@fopen($img_link, 'r')) {
                    $novel->cover = '/cover/cover_default.jpg';
                } else {
                    $cover_ext = substr($img_link, strrpos($img_link, '.')+1);
                    $path = public_path('cover/'.$novel->id.'_cover.'.$cover_ext);
                    //文件不存在时才获取图片
                    if(!file_exists($path) || getFileSize($img_link)==44110) {
                        $cover = file_get_contents($img_link);
                        file_put_contents($path, $cover);
                    }
                    $novel->cover = '/cover/'.$novel->id.'_cover.'.$cover_ext;
                }
                $novel->save();
            }
            return $novel;
        }
        return false;
        // TODO: Implement getSingleNovel() method.
    }

    public function update(Novel $novel)
    {

    }

    /**
     * 采集小说章节，不需考虑更新问题
     * @param Novel $novel
     * @return string|void
     */
    public function snatch(Novel $novel)
    {
        $novel_html = $this->send(self::DOMAIN . $novel->source_link);
        $source_arr = explode('/', $novel->source_link);
        $source_book = $source_arr[2];

        $chapter_list = $this->getChapterList($novel_html);
        if(!$chapter_list[1]) {
            Log::error('getChapterList failed');
            return ['code' => 0];
        }
//        $chapter_link_arr = $chapter_list[1];
        $total_num = count($chapter_list[1]);
        $loop_num = ceil($total_num/$this->page_size);

        for ($i=0; $i<$loop_num; $i++)
        {
            $splice_list = [];
            $splice_list[1] = array_slice($chapter_list[1], $i*$this->page_size, $this->page_size);
            $contents = $this->multi_send_test($splice_list[1], self::DOMAIN, $this->page_size);
            $temp = [];
            $now = Carbon::now();
            foreach ($contents as $k => $html) {
                preg_match('/<script>showContent\(\"(.*?)\", \"(.*?)\"\);<\/script>/s', $html, $read_match);
                if(@$read_match[2]){
                    $chapter_id = $read_match[2];
                    $content = $this->getChapterContent($source_book, $chapter_id);
                    $name = $this->getChapterName($html);
                    $source_arr[count($source_arr)-1] = $chapter_id.'.html';

                    $temp[$chapter_id] = [
                        'source_link' => self::DOMAIN . implode('/', $source_arr),
                        'name' => $name,
                        'content' => $content,
                        'novel_id' => $novel->id,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }
            }
            ksort($temp);
            $temp = array_values($temp);
            Chapter::insert($temp);
        }
        $novel->chapter_num = $total_num;
        $novel->save();
        return ['code' => 1];
    }

    private function getChapterList($html)
    {
        preg_match_all('/<dd><a href="(.*?)" title=".*?">.*?<\/a><\/dd>/s', $html, $matches);
        return $matches;
    }

    private function getChapterName($html)
    {
        preg_match('/<h1 class="chapter_title" >(.*?)<\/h1>/s', $html, $matches);
        return $matches[1];
    }

    private function getChapterContent($bookid, $chapterid)
    {
        $request_url = self::DOMAIN . '/modules/article/show.php';
        $params = [
            'aid' => $bookid,
            'cid' => $chapterid,
            'r' => mt_rand(0, 10000000000)/10000000000
        ];
        return $this->send($request_url, 'POST', $params, '');
    }

}