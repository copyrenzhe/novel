<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Novel;
use Illuminate\Http\Request;

use App\Http\Requests;

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
        $TopNovels = Novel::top()->get();
        $LastNovels = Novel::last()->get();
        return view('index.index', compact('TopNovels', 'LastNovels'));
    }

    public function category($category)
    {
        $novels = Novel::where('type', '=', $category)->paginate(30);
        $genres = $this->genres;
        return view('index.category', compact('category', 'novels', 'genres'));
    }

    public function search($keywords)
    {
        $novels = Novel::where('name', 'like', '%'.$keywords.'%')->paginate(15);
        return view('index.search', compact('keywords', 'novels'));
    }
}
