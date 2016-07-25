<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Http\Request;

use App\Http\Requests;

class CommonController extends Controller
{
    //

    /**
     * CommonController constructor.
     */
    public function __construct()
    {
        $HotNovels = Novel::hot()->get();
        $genres = [
            'xuanhuan'  =>  '玄幻小说',
            'xiuzhen'   =>  '修真小说',
            'dushi'     =>  '都市小说',
            'lishi'     =>  '历史小说',
            'wangyou'   =>  '网游小说',
            'kehuan'    =>  '科幻小说',
            'other'     =>  '其他'
        ];
        view()->composer('common.right', function($view) use($HotNovels, $genres) {
            $view->with('HotNovels', $HotNovels)->with('genres', $genres);
        });
    }
}
