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
            return Novel::with('author')->hot()->take(8)->get();
        });
        $genres = Cache::rememberForever('genres', function() {
            return category_maps();
        });
        $this->genres = $genres;
        view()->composer(['common.right', 'common.navbar'], function($view) use($HotNovels, $genres) {
            $view->with('HotNovels', $HotNovels)->with('genres', $genres);
        });
    }
}
