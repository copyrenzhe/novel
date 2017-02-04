@extends('app')
@section('title'){{ $name }}-书虫网@stop
@section('keywords'){{ $name }},书虫网{{ $name }}@stop
@section('content')
    <div id="left">
        <h1 class="title">{{ $name }}</h1>
        <h4 class="desc margin-less"></h4>
        <div class="l-category box">
            <ul class="content">
                @foreach($novels as $novel)
                <li>
                    <a class="c-title" href="{{ route('book', ['bookId' => $novel->id]) }}" title="{{ $novel->name }}">{{ $novel->name }}</a>
                    <a class="tag tag-novel-type" href="{{ route('category', ['category' => $novel->type]) }}">#{{ category_maps()[$novel->type] }}</a>
                    <a class="tag tag-novel-status" href="{{ $novel->is_over ? route('over') : 'javascript:void(0)' }}">#{{ $novel->is_over ? '完结' : '连载中' }}</a>
                    <a href="{{ route('author', ['authorId' => $novel->author_id]) }}" title="{{ $novel->author->name }}" class="cate-li-right">{{ $novel->author->name }}</a>
                </li>
                @endforeach
            </ul>
            <div class="pagination">
                @include('pagination.novel', ['paginator' => $novels])
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
    <!--/ left -->
    <!-- right -->
    @include('common.right')
    <div class="clr"></div>
@stop