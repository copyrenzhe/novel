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

        $hotNovels = [];
        $hotNovels['total'] = \Redis::zRevRangeByScore(config('cache.redis.view_total'), -inf, +inf,
            ['withscores' => true, 'limit' => [0, 8]]);
        $hotNovels['month'] = \Redis::zRevRangeByScore(config('cache.redis.view_month'), -inf, +inf,
            ['withscores' => true, 'limit' => [0, 8]]);
        $hotNovels['week'] = \Redis::zRevRangeByScore(config('cache.redis.view_week'), -inf, +inf,
            ['withscores' => true, 'limit' => [0, 8]]);

        $genres = Cache::rememberForever('genres', function() {
            return category_maps();
        });
        $this->genres = $genres;
        view()->composer(['common.right', 'common.navbar'], function($view) use($hotNovels, $genres) {
            $view->with('hotNovels', $hotNovels)->with('genres', $genres);
        });
    }
}
