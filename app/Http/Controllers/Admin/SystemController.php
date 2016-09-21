<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    //
    public function index()
    {
        return view('admin.system.index');
    }

    public function updateAll()
    {
        return ;
    }
}
