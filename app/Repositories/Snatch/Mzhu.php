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

    private $source = 'mzhu8';

    public function getNovelList()
    {
        $list_url = [
            'http://www.mzhu8.com/mulu/6/1.html',   //国外名著
        ];
        foreach ($list_url as $link){
            $this->getNovelListAction($link);
        }
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
            $novelLinks = array_merge($novelLinks, $matches);
        }
        return $novelLinks;
    }

    public function getSingleNovel($link)
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
                $novel->type = 'other';
                $novel->description = $description;
                if(getFileSize($img_link)==44110) {
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

    public function getChapterNew(Novel $novel)
    {
        // TODO: Implement getChapterNew() method.
    }

    public function snatchChapter(Novel $novel)
    {
        $novel_html = $this->send(self::DOMAIN . $novel->source_link);
        $source_arr = explode('/', $novel->source_link);
        $source_book = $source_arr[2];

        $chapter_list = $this->getChapterList($novel_html);
        if(!$chapter_list[1]) {
            Log::error('getChapterList failed');
            return ['code' => 0];
        }
        $chapter_link_arr = $chapter_list[1];
        foreach ($chapter_link_arr as $link){

        }
        $total_num = count($chapter_list[1]);
        $loop_num = ceil($total_num/$this->page_size);

        for ($i=0; $i<$loop_num; $i++)
        {
            $splice_list = [];
            $splice_list[1] = array_slice($chapter_list[1], $i*$this->page_size, $this->page_size);
            $splice_list[2] = array_slice($chapter_list[2], $i*$this->page_size, $this->page_size);
            $contents = $this->multi_send_test($splice_list[1], self::DOMAIN . $novel->source_link, $this->page_size);
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
                    'source_link' => self::DOMAIN . $novel->source_link . $splice_list[1][$k],
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

    public function getChapterList($html)
    {
        preg_match_all('/<dd><a href="(.*?)" title=".*?">.*?<\/a><\/dd>/s', $html, $matches);
        return $matches;
    }

    public function getChapterContent($bookid, $chapterid)
    {
        $request_url = 'modules/article/show.php';
    }

    public function getSource()
    {
        return $this->source;
    }
}