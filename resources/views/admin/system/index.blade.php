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
            @include('admin.partials.errors')
            @include('admin.partials.success')
            <div class="box-header">
                <h3 class="box-title">工具列表</h3>
            </div>
            <div class="box-body">
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
                <p>初始化新小说（目前只支持笔趣阁）（包含采集小说章节）</p>
                <form action="{{ url('/admin/system/init') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group margin">
                        <input type="text" class="form-control" name="link" placeholder="输入小说地址">
                        <span class="input-group-btn" style="width: 250px;">
                            <select name="source" id="" class="form-control">
                                <option value="biquge" selected>笔趣阁----www.qu.la</option>
                                <option value="kanshuzhong">看书中----www.kanshuzhong.com</option>
                                <option value="mzhu">名著阅读网----www.mzhu8.com</option>
                            </select>
                        </span>
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-info btn-flat">初始化</button>
                        </span>
                    </div>
                </form>
                <p>采集小说</p>
                <form action="{{ url('/admin/system/snatch') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group margin">
                        <input type="text" class="form-control" name="novel_id" placeholder="输入小说id">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-info btn-flat">采集</button>
                        </span>
                    </div>
                </form>
                <p>更新小说</p>
                <form action="{{ url('/admin/system/update') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group margin">
                        <input type="text" class="form-control" name="novel_id" placeholder="输入小说id，以逗号分开">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-info btn-flat">更新</button>
                        </span>
                    </div>
                </form>
                <p>强制修复小说</p>
                <form action="{{ url('/admin/system/repair') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group margin">
                        <input type="text" class="form-control" name="novel_id" placeholder="输入小说id，以逗号分开">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-info btn-flat">更新</button>
                        </span>
                    </div>
                </form>
                <p>统计小说章节数</p>
                <form action="{{ url('/admin/system/sumSingle') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group margin">
                        <input type="text" class="form-control" name="novel_id" placeholder="输入小说id，以逗号分开">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-info btn-flat">更新</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection