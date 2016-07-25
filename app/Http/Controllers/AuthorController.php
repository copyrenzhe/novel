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
        return view('author.info', compact('author'));
    }

    public function all()
    {
        $authors = Author::all();
        return view('author.all', compact('authors'));
    }
}
