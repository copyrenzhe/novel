@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('message.pagenotfound') }}
@endsection

@section('contentheader_title')
    {{ trans('message.404error') }}
@endsection

@section('$contentheader_description')
@endsection

@section('main-content')

<div class="error-page">
    <h2 class="headline text-yellow"> 404</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> Oops! {{ trans('message.pagenotfound') }}.</h3>
        <p>
            {{ trans('message.notfindpage') }}
            {{ trans('message.mainwhile') }} <a href='{{ url('/home') }}'>{{ trans('message.returndashboard') }}</a> {{ trans('message.usingsearch') }}
        </p>
        <form class='search-form'>
            <div class='input-group'>
                <input type="text" name="search" class='form-control' placeholder="{{ trans('message.search') }}"/>
                <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
                </div>
            </div><!-- /.input-group -->
        </form>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection