<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Novel;
use Illuminate\Http\Request;

use App\Http\Requests;

class AuthorController extends CommonController
{
    /**
     * AuthorController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function info($authorId)
    {
        $author = Author::find($authorId);
        $novels = Novel::where('author_id', $authorId)->paginate(30);
        $name = $author->name;
        return view('index.list', compact('author', 'name', 'novels'));
    }

    public function all()
    {
        $authors = Author::all();
        $name = '所有作者';
        return view('index.list', compact('authors', 'name'));
    }
}
