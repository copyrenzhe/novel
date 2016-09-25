@extends('layouts.app')

@section('styles')
@endsection

@section('htmlheader_title')
    系统
@endsection

@section('contentheader_title')
    系统工具
@endsection

@section('main-content')
    <div class="row">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">工具列表</h3>
            </div>
            <div class="box-body">
                @if( Session::has('system_message') )
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Alert!</h4>
                        {{ Session::get('system_message') }}
                    </div>
                @endif
                <a href="{{ url('/admin/system/updateAllNovels') }}" class="btn btn-app">
                    <i class="fa fa-book"></i>
                    更新小说
                </a>
                <a href="" class="btn btn-app">
                    <i class="fa fa-history"></i>
                    修复章节
                </a>
                <a href="{{ url('/admin/system/sumChapters') }}" class="btn btn-app">
                    <i class="fa fa-line-chart"></i>
                    更新统计
                </a>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">采集相关</h3>
            </div>
            <div class="box-body">
                <p>采集新小说（目前只支持笔趣阁）</p>
                <form action="{{ url('/admin/system/snatch') }}" method="POST">
                    <div class="input-group margin">
                        <input type="text" class="form-control" name="link" placeholder="输入小说地址">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-info btn-flat">采集</button>
                        </span>
                    </div>
                </form>
                <p>更新小说章节</p>
                <form action="{{ url('/admin/system/update') }}" method="post">
                    <div class="input-group margin">
                        <input type="text" class="form-control" name="novel_id" placeholder="输入小说id">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-info btn-flat">更新</button>
                        </span>
                    </div>
                </form>
        </div>
    </div>
@endsection