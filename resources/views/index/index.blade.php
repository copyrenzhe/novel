@extends('app')
@section('content')
    <!-- left -->
    <div id="left">
        <!-- Truyen hot -->
        <h2 class="title">热门推荐</h2>
        <div class="l-grid">
            <div class="e-wrapper">
                @foreach($TopNovels as $novel)
                <div class="element">
                    <a href="{{ route('book', ['bookId' => $novel->id]) }}" class="crop" title="{{ $novel->name }}"><img class="thumb" src="{{ $novel->cover }}" border="0" alt="{{ $novel->name }}" /></a>
                    <div class="content">
                        <a class="e-title" href="{{ route('book', ['bookId' => $novel->id]) }}" title="{{ $novel->name }}" >{{ $novel->name }}</a>
                        <span class="e-view">热度: {{ $novel->hot }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!--/ Truyen hot -->
        <!-- truyen moi cap nhat -->
        <h1 class="title"><a href="/" title="Books online free" >最近更新</a></h1>
        <div class="l-category box category-home">
            <ul class="content">
                @foreach($LastNovels as $novel)
                <li>
                    <a href="{{ route('author', ['authorId' => $novel->author_id]) }}" title="{{ $novel->author->name }}" class="cate-li-right">{{ $novel->author->name }}</a>
                    <a class="c-title" href="{{ route('book', ['bookId' => $novel->id]) }}" title="{{ $novel->name }}">{{ $novel->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        <!--/ truyen moi cap nhat -->
    </div>
    <!--/ left -->
    @include('common.right')
    <div class="clr"></div>
@stop
