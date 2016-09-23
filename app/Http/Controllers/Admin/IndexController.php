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
        $count['novel'] = [
            'all' => Novel::count(),
            'xuanhuan' => Novel::where('type', 'xuanhuan')->count(),
            'xiuzhen' => Novel::where('type', 'xiuzhen')->count(),
            'dushi' => Novel::where('type', 'dushi')->count(),
            'lishi' => Novel::where('type', 'lishi')->count(),
            'wangyou' => Novel::where('type', 'wangyou')->count(),
            'kehuan' => Novel::where('type', 'wangyou')->count(),
            'other' => Novel::where('type', 'other')->count()
        ];
        $count['user'] = User::count();
        $count['chapter'] = Chapter::count();
        return view('admin.index', compact('count'));
    }
}
