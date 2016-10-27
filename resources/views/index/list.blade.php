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
                    <a href="{{ route('author', ['authorId' => $novel->author_id]) }} }}" title="{{ $novel->author->name }}" class="cate-li-right">{{ $novel->author->name }}</a>
                    <a class="c-title" href="{{ route('book', ['bookId' => $novel->id]) }}" title="Destroyed">{{ $novel->name }}</a>
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