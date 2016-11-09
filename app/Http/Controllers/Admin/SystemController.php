<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SnatchChapters;
use App\Jobs\SnatchInit;
use App\Jobs\SnatchRepair;
use App\Jobs\SnatchUpdate;
use App\Jobs\SumOfChapters;
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
        return redirect('/admin/system')->withSuccess('更新小说任务已加入后台队列');
    }

    public function sumChapter()
    {
        Artisan::queue('sum:chapter --queue');
        return redirect('/admin/system')->withSuccess('更新各小说章节数量任务已加入后台队列');
    }

    public function update(Request $request)
    {
        $ids = $request->input('novel_id');
        $idArr = explode(',', $ids);
        $this->dispatch(new SnatchUpdate($idArr));
        return redirect('/admin/system')->withSuccess('更新'.$ids.'小说任务已加入后台队列');
    }

    public function init(Request $request)
    {
        $link = $request->input('link');
        $source = $request->input('source');
        $this->dispatch(new SnatchInit($link, $source));
        return redirect('/admin/system')->withSuccess('初始化小说'.$link.'任务已加入后台队列');
    }

    public function snatch(Request $request)
    {
        $ids = $request->input('novel_id');
        $idArr = explode(',', $ids);
        $this->dispatch(new SnatchChapters($idArr));
        return redirect('/admin/system')->withSuccess('采集'.$ids.'小说任务已加入后台队列');
    }

    public function repair(Request $request)
    {
        $ids = $request->input('novel_id');
        $idArr = explode(',', $ids);
        $this->dispatch(new SnatchRepair($idArr, true));
        return redirect('/admin/system')->withSuccess('修复'.$ids.'小说任务已加入后台队列');
    }

    public function sumSingle(Request $request)
    {
        $ids = $request->input('novel_id');
        $idArr = explode(',', $ids);
        $this->dispatch(new SumOfChapters($idArr, true));
        return redirect('/admin/system')->withSuccess('修复'.$ids.'小说任务已加入后台队列');
    }
}
