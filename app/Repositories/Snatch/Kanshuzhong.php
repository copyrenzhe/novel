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

class Kanshuzhong extends Snatch implements SnatchInterface
{
    private $source = 'kanshuzhong';

    public static function init($link)
    {
        $Kanshuzhong = new Kanshuzhong();
        return $Kanshuzhong->getSingleNovel($link);
    }

    public function getNovelList()
    {
        
    }

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
                $novel->source = $this->source;
                $novel->source_link = strstr($link, '/book');
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

    public function getNovelChapter(Novel $novel)
    {
        return ;
    }

    public function getSource()
    {
        return $this->source;
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