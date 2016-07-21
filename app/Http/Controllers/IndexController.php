<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Http\Request;

use App\Http\Requests;

class IndexController extends Controller
{
    public function index()
    {
        $TopNovels = Novel::top()->get();
        $HotNovels = Novel::hot()->get();
        $LastNovels = Novel::last()->get();
        $genres = [
            'xuanhuan'  =>  '玄幻小说',
            'xiuzhen'   =>  '修真小说',
            'dushi'     =>  '都市小说',
            'lishi'     =>  '历史小说',
            'wangyou'   =>  '网游小说',
            'kehuan'    =>  '科幻小说',
            'other'     =>  '其他'
        ];

        view('index', compact('TopNovels', 'HotNovels', 'LastNovels', 'genres'));
    }

    public function search($keywords)
    {
        $novels = Novel::where('name', 'like', '%'.$keywords.'%')->get();
        view('search', compact('novels'));
    }
}
