<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NovelUpdateRequest;
use App\Jobs\SnatchRepair;
use App\Jobs\SnatchUpdate;
use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class NovelsController extends Controller
{
    protected $fields = [
        'name' => '',
        'description' => '',
        'author_id' => '',
        'type' => '',
        'cover' => '',
        'hot'=> 0,
        'sort' => 0,
        'is_over' => 0,
        'biquge_url' => '',
        'chapter_num' => 0
    ];


    public function index()
    {
        $novels = Novel::all();
        return view('admin.novels.index', compact('novels'));
    }

    public function datatables(Request $request)
    {
        $novel = Novel::leftJoin('author', 'novel.author_id', '=', 'author.id')
            ->select(['novel.id', 'novel.name', 'author.name as a_name', 'novel.type', 'novel.hot', 'novel.chapter_num', 'novel.is_over', 'novel.created_at', 'novel.updated_at']);
        return Datatables::of($novel)
            ->addColumn('operations',
                '
                <a style="margin:3px;" href="/admin/novels/{{ $id }}/snatchUpdate" class="X-Small btn-xs text-success "><i class="fa fa-line-chart"></i> 更新</a>
                <a style="margin:3px;" href="/admin/novels/{{ $id }}/snatchRepair" class="X-Small btn-xs text-success "><i class="fa fa-history"></i> 修复</a>
                <a style="margin:3px;" href="/admin/novels/{{ $id }}/edit" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>
                <a style="margin:3px;" href="#" attr="{{ $id }}" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>')
            ->editColumn('is_over', '@if($is_over)
                                        是
                                    @else
                                        否
                                    @endif')
            ->editColumn('type', '@if($type=="xuanhuan")
                                        玄幻
                                @elseif($type=="xiuzhen")
                                        修真
                                @elseif($type=="dushi")
                                        都市
                                @elseif($type=="lishi")
                                        历史
                                @elseif($type=="wangyou")
                                网游
                                @elseif($type=="kehuan")
                                科幻
                                @else
                                其他
                                @endif')
            ->filter(function($query) use($request){
                if($filterValue = $request->get('search')['value']) {
                    $query->where('novel.id', 'LIKE', '%'.$filterValue.'%')
                        ->orWhere('novel.name', 'LIKE', '%'.$filterValue.'%');
                }
            })
            ->make(true);
    }
    public function snatchUpdate($novel_id)
    {
        $this->dispatch(new SnatchUpdate($novel_id));
        $novel = Novel::find($novel_id);
        return redirect('admin/novels/index')->withSuccess('小说:'.$novel->name.'加入更新队列');
    }



    public function snatchRepair($novel_id)
    {
        $this->dispatch(new SnatchRepair($novel_id));
        $novel = Novel::find($novel_id);
        return redirect('admin/novels/index')->withSuccess('小说:'.$novel->name.'加入修复队列');
    }


    public function create()
    {
    }

    public function store()
    {
    }

    public function show($novel_id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $novel_id
     * @return \Illuminate\Http\Response
     */
    public function edit($novel_id)
    {
        $novel = Novel::find((int)$novel_id);
        $data = ['id' => (int)$novel_id];
        foreach(array_keys($this->fields) as $field) {
            $data[$field] = old($field, $novel->$field);
        }

        return view('admin.novels.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NovelUpdateRequest|Request $request
     * @param  int $novel_id
     * @return \Illuminate\Http\Response
     */
    public function update(NovelUpdateRequest $request, $novel_id)
    {
        $novel = Novel::find((int)$novel_id);
        foreach (array_keys($this->fields) as $field) {
            $novel->$field = $request->get($field);
        }
        $novel->save();
        return redirect('admin/novels')->withSuccess('修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $novel_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($novel_id)
    {
        $chapter = Chapter::where('novel_id', $novel_id)->first();

        if($chapter) {
            return redirect()->back()->withErrors('请先清空该小说的章节');
        }
        $novel = Novel::find((int)$novel_id);
        if($novel) {
            $novel->delete();
        } else {
            return redirect()->back()->withErrors('删除失败');
        }
        return redirect()->back()->withSuccess('删除成功');
    }
}
