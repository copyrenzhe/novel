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
                <a href="" class="btn btn-app">
                    <i class="fa fa-book"></i>
                    更新小说
                </a>
                <a href="" class="btn btn-app">
                    <i class="fa fa-history"></i>
                    修复章节
                </a>
                <a href="" class="btn btn-app">
                    <i class="fa fa-line-chart"></i>
                    更新统计
                </a>
            </div>
        </div>
    </div>
@endsection