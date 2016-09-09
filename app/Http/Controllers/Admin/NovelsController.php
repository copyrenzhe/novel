<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SnatchRepair;
use App\Jobs\SnatchUpdate;
use App\Models\Novel;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
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

    public function datatables()
    {
        $novel = Novel::leftJoin('author', 'novel.author_id', '=', 'author.id')
            ->select(['novel.id', 'novel.name', 'author.name as a_name', 'novel.type', 'novel.hot', 'novel.chapter_num', 'novel.created_at', 'novel.updated_at']);
        return Datatables::of($novel)
            ->addColumn('operations', '<a class="btn btn-primary col-sm-5" href="{{ url(\'admin/novels/snatchUpdate/\'.$id) }}">更新</a><a class="btn btn-primary col-sm-5 col-sm-offset-2" href="{{ url(\'admin/novels/snatchRepair/\'.$id) }}">修复</a>')
            ->make(true);
    }

    public function snatchUpdate($novel_id)
    {
        $this->dispatch(new SnatchUpdate($novel_id));
    }

    public function snatchRepair($novel_id)
    {
        $this->dispatch(new SnatchRepair($novel_id));
    }
}
