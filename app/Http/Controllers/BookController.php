<?php

namespace App\Http\Controllers;

use App\Events\Event;
use App\Events\NovelView;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
use App\Models\UserNovel;
use Illuminate\Http\Request;

use App\Http\Requests;

class BookController extends CommonController
{
    protected $user;

    //
    /**
     * BookController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->user = session('wechat.oauth_user');
    }

    public function index($bookId, $openId='')
    {
        $subList = $this->subList($openId);
        $novel = Novel::find($bookId);
        $recentChapter = Chapter::where('novel_id', $bookId)->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->first();
        $genres = $this->genres;
        return view('book.index', compact('novel', 'recentChapter', 'genres', 'openId', 'subList'));
    }

    public function chapter($bookId, $chapterId, $openId='')
    {
        $subList = $this->subList($openId);
        $chapter = Chapter::where('novel_id', '=', $bookId)->find($chapterId);
        $prev = Chapter::where('novel_id', $bookId)->where('id', '<', $chapterId)->first();
        $next = Chapter::where('novel_id', $bookId)->where('id', '>', $chapterId)->first();
        Event::fire(new NovelView($chapter));
        return view('book.chapter', compact('chapter', 'prev', 'next', 'subList'));
    }

    protected function subList($openId)
    {
        $openId = $this->user ? $this->user->getId : $openId;
        $subList = [];
        $openId && $subList = User::where('open_id', $openId)->first()->novel()->pluck('novel_id')->toArray();
        return $subList;
    }

    public function subNovel(Request $request)
    {
        $userId = User::where('open_id', $request->input('openId'))->first()->id;
        UserNovel::firstOrCreate(['user_id' => $userId, 'novel_id' => $request->input('novelId')]);
        return true;
    }
}
