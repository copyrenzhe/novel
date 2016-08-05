<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{
    //
    protected $genres;

    /**
     * CommonController constructor.
     */
    public function __construct()
    {
        $HotNovels = Cache::remember('HotNovels', 60, function() {
            return $HotNovels = Novel::hot()->get();
        });
        $genres = Cache::rememberForever('genres', function() {
            return [
                'xuanhuan'  =>  '玄幻小说',
                'xiuzhen'   =>  '修真小说',
                'dushi'     =>  '都市小说',
                'lishi'     =>  '历史小说',
                'wangyou'   =>  '网游小说',
                'kehuan'    =>  '科幻小说',
                'other'     =>  '其他'
            ];
        });
        $this->genres = $genres;
        view()->composer(['common.right', 'common.navbar'], function($view) use($HotNovels, $genres) {
            $view->with('HotNovels', $HotNovels)->with('genres', $genres);
        });
    }
}
