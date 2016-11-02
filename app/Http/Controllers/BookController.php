<?php

namespace App\Http\Controllers;

use App\Events\NovelView;
use App\Events\RepairChapterEvent;
use App\Events\RepairNovelEvent;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
use App\Models\UserNovel;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Event;

class BookController extends CommonController
{
    protected $user;

    //
    /**
     * BookController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct();
        $user = session('wechat.oauth_user');
        if($openId = $request->route('openId')){
            $user = User::where('open_id', $openId)->first();
        }
        $user && view()->composer(['book.index', 'book.chapter'], function($view) use($user) {
            $view->with('user', $user);
        });
    }

    public function index($bookId, $openId='')
    {
        $subList = $this->subList($openId);
        $genres = $this->genres;
        $novel = Novel::findOrFail($bookId);
        $otherBooks = Novel::where('author_id', $novel->author_id)->where('id', '!=', $novel->id)->pluck('name')->implode(',');
        if($novel && !$novel->chapter){
            Event::fire(new RepairNovelEvent($novel));
        }
        return view('book.index', compact('novel', 'genres', 'subList', 'otherBooks'));
    }

    public function chapter($bookId, $chapterId, $openId='')
    {
        $subList = $this->subList($openId);
        $chapter = Chapter::with('novel')->where('novel_id', '=', $bookId)->findOrFail($chapterId);
        if($chapter && !$chapter->content){
            Event::fire(new RepairChapterEvent($chapter));
        }
        $prev = Chapter::where('novel_id', $bookId)->where('id', '<', $chapterId)->orderBy('id', 'desc')->first();
        $next = Chapter::where('novel_id', $bookId)->where('id', '>', $chapterId)->orderBy('id', 'asc')->first();
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

    public function subscribe(Request $request)
    {
        $novel_id = $request->get('book_id');
        $user_id = $request->get('user_id');
        $userNovel = UserNovel::where('novel_id', $novel_id)->where('user_id', $user_id)->first();
        if($userNovel){
            //取消订阅
            UserNovel::where('novel_id', $novel_id)->where('user_id', $user_id)->delete();
            $isSubscribe = 0;
        } else {
            //订阅
            UserNovel::create(['novel_id'=> $novel_id, 'user_id' =>$user_id]);
            $isSubscribe = 1;
        }
        return response()->json([
            'status' => 1,
            'isSubscribe' => $isSubscribe
        ]);
    }
}
