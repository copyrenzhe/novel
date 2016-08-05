<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;

use App\Http\Requests;

class BookController extends CommonController
{

    //
    /**
     * BookController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index($bookId)
    {
        $novel = Novel::find($bookId);
        $novel->increment('hot');
        $recentChapter = Chapter::where('novel_id', $bookId)->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->first();
        $genres = $this->genres;
        return view('book.index', compact('novel', 'recentChapter', 'genres'));
    }

    public function chapter($bookId, $chapterId)
    {
        $chapter = Chapter::where('novel_id', '=', $bookId)->find($chapterId);
        $prev = Chapter::where('novel_id', $bookId)->where('id', '<', $chapterId)->first();
        $next = Chapter::where('novel_id', $bookId)->where('id', '>', $chapterId)->first();
        if(!$chapter)
            abort(404);
        $chapter->increment('views');
        $chapter->novel()->increment('hot');
        return view('book.chapter', compact('chapter', 'prev', 'next'));
    }
}
