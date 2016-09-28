<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SnatchChapters;
use App\Jobs\SnatchInit;
use App\Jobs\SnatchRepair;
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
        $ids = $request->input('novel_id');
        $idArr = explode(',', $ids);
        $this->dispatch(new SnatchUpdate($idArr));
        Session::flash('system_message', '更新'.$ids.'小说任务已加入后台队列');
        return redirect('/admin/system');
    }

    public function init(Request $request)
    {
        $link = $request->input('link');
        $this->dispatch(new SnatchInit($link));
        Session::flash('system_message', '初始化小说任务已加入后台队列');
        return redirect('/admin/system');
    }

    public function snatch(Request $request)
    {
        $ids = $request->input('novel_id');
        $idArr = explode(',', $ids);
        $this->dispatch(new SnatchChapters($idArr));
        Session::flash('system_message', '采集'.$ids.'小说任务已加入后台队列');
        return redirect('/admin/system');
    }

    public function repair(Request $request)
    {
        $ids = $request->input('novel_id');
        $idArr = explode(',', $ids);
        $this->dispatch(new SnatchRepair($idArr, true));
        Session::flash('system_message', '修复'.$ids.'小说任务已加入后台队列');
        return redirect('/admin/system');
    }
}
