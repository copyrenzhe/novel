<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
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
        $openId = $this->user ? $this->user->getId : $openId;
        if($openId){
            $user = User::find($openId);
        }
        $novel = Novel::find($bookId);
        $novel->increment('hot');
        $recentChapter = Chapter::where('novel_id', $bookId)->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->first();
        $genres = $this->genres;
        return view('book.index', compact('novel', 'recentChapter', 'genres', 'openId'));
    }

    public function chapter($bookId, $chapterId, $openId='')
    {
        $openId = $this->user ? $this->user->getId : $openId;
        if($openId){
            $user = User::find($openId);
        }
        $chapter = Chapter::where('novel_id', '=', $bookId)->find($chapterId);
        $prev = Chapter::where('novel_id', $bookId)->where('id', '<', $chapterId)->first();
        $next = Chapter::where('novel_id', $bookId)->where('id', '>', $chapterId)->first();
        if(!$chapter)
            abort(404);
        $chapter->increment('views');
        $chapter->novel()->increment('hot');
        return view('book.chapter', compact('chapter', 'prev', 'next', 'openId'));
    }
}
