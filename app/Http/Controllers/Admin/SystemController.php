<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SnatchInit;
use App\Jobs\SnatchUpdate;
use Artisan;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

class SystemController extends Controller
{
    //
    public function index()
    {
        return view('admin.system.index');
    }

    public function updateAll()
    {
        Artisan::queue('snatch:update --queue');
        Session::flash('system_message', '更新小说任务已加入后台队列');
        return redirect('/admin/system');
    }

    public function sumChapter()
    {
        Artisan::queue('sum:chapter --queue');
        Session::flash('system_message', '更新各小说章节数量任务已加入后台队列');
        return redirect('/admin/system');
    }

    public function update(Request $request)
    {
        $ids = $request->input('id');
        $idArr = explode(',', $ids);
        $this->dispatch(new SnatchUpdate($idArr));
        Session::flash('system_message', '更新'.$ids.'小说任务已加入后台队列');
        return redirect('/admin/system');
    }

    public function snatch(Request $request)
    {
        $link = $request->input('link');
        $this->dispatch(new SnatchInit($link));
        Session::flash('system_message', '采集小说任务已加入后台队列');
        return redirect('/admin/system');
    }
}
