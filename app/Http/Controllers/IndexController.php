<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Novel;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

class IndexController extends CommonController
{
    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $TopNovels = Cache::remember('TopNovels', 60, function() {
            return Novel::top()->take(8)->get();
        });
        $LastNovels = Novel::last()->take(15)->get();
        return view('index.index', compact('TopNovels', 'LastNovels'));
    }

    public function category($category)
    {
        $novels = Novel::where('type', '=', $category)->paginate(30);
        $genres = $this->genres;
        $name = $genres[$category];
        return view('index.list', compact('category', 'novels', 'genres', 'name'));
    }

    public function newRelease()
    {
        $novels = Novel::orderBy('updated_at', 'desc')->paginate(30);
        $name = '最新发布';
        return view('index.list', compact('novels', 'name'));
    }

    public function top()
    {
        $novels = Novel::hot()->paginate(30);
        $name = '排行榜单';
        return view('index.list', compact('novels', 'name'));
    }

    public function over()
    {
        $novels = Novel::over()->hot()->paginate(30);
        $name = '完结小说';
        return view('index.list', compact('novels', 'name'));
    }

    public function search($keywords)
    {
        $authors = Author::where('name', 'like', '%'.$keywords.'%')->pluck('id')->toArray();
        $novels = Novel::where('name', 'like', '%'.$keywords.'%')
                    ->orwhereIn('author_id', $authors)->paginate(30);
        $name = "关键词：".$keywords;
        return view('index.list', compact('name', 'novels'));
    }
}
