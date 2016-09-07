<?php

namespace App\Http\Controllers\Admin;

use App\Models\Chapter;
use App\Models\Novel;
use App\Http\Requests;
use App\Models\User;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $count = [];
        $count['novel'] = Novel::count();
        $count['user'] = User::count();
        $count['chapter'] = Chapter::count();
        return view('admin.index', compact('count'));
    }
}
