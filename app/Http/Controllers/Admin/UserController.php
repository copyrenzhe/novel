<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    
    public function datatables()
    {
        $users = User::select(['id', 'open_id', 'nickname', 'is_subscribe', 'push_time', 'created_at', 'updated_at']);
        return Datatables::of($users)
            ->editColumn('is_subscribe', function ($data){ return $data->is_subscrie ? '是' : '否'; })
            ->addColumn('operations', '<a class="btn btn-primary col-sm-5" href="{{ url(\'admin/novels/edit/\'.$id) }}">编辑</a><a class="btn btn-primary col-sm-5 col-sm-offset-2" href="{{ url(\'admin/users/delete/\'.$id) }}">删除</a>')
            ->make(true);
    }

    public function show()
    {
        return ;
    }
}
