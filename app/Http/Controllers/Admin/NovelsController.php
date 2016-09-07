<?php

namespace App\Http\Controllers\Admin;

use App\Models\Novel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NovelsController extends Controller
{
    //
    public function index()
    {
        $novels = Novel::all();
        return view('admin.novels.index', compact('novels'));
    }
}
