<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use App\Models\Novel;
use App\Models\Author;
use App\Models\Feedback;
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
        $LastNovels = Novel::with('author')->latest()->take(15)->get();
        return view('index.index', compact('TopNovels', 'LastNovels'));
    }

    public function category($category)
    {
        $novels = Novel::with('author')->where('type', '=', $category)->paginate(30);
        $genres = $this->genres;
        $name = $genres[$category];
        return view('index.list', compact('category', 'novels', 'genres', 'name'));
    }

    public function newRelease()
    {
        $novels = Novel::with('author')->latest()->paginate(30);
        $name = '最新发布';
        return view('index.list', compact('novels', 'name'));
    }

    public function top()
    {
        $novels = Novel::with('author')->hot()->paginate(30);
        $name = '排行榜单';
        return view('index.list', compact('novels', 'name'));
    }

    public function over()
    {
        $novels = Novel::with('author')->over()->hot()->paginate(30);
        $name = '完结小说';
        return view('index.list', compact('novels', 'name'));
    }

    public function search(Request $request)
    {
        $keywords = $request->get('keyword');
        $authors = Author::where('name', 'like', '%'.$keywords.'%')->pluck('id')->toArray();
        $novels = Novel::where('name', 'like', '%'.$keywords.'%')
                    ->orwhereIn('author_id', $authors)->paginate(30);
        $name = "关键词：".$keywords;
        return view('index.list', compact('name', 'novels'));
    }

    public function feedback()
    {
        return view('index.feedback');
    }

    public function postFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:20',
            'url' => 'max:100',
            'name' => 'required|max:20',
            'email' => 'required|email',
            'content' => 'required|max:500'
        ]);

        if($validator->fails()) {
            return redirect('feedback')
                ->withInput()
                ->withErrors($validator);
        }

        $feedback = Feedback::create($request->input());
        if($feedback) {
            Session::flash('flash_message', '提交成功!');
            return redirect('/');
        }
    }
}
