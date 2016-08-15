@extends('app')
@section('content')
    <div id="left">
        <!-- truyen moi cap nhat -->
        <h1 class="title">关键词: {{ $keywords }}</h1>
        <h4 class="desc margin-less"></h4>
        <div class="l-category box">
            <ul class="content">
                @foreach($novels as $novel)
                <li><a href="/author/{{ $novel->author_id }}" title="{{ $novel->author->name }}" class="cate-li-right">{{ $novel->author->name }}</a>
                    <a class="c-title" href="/book/{{ $novel->id }}" title="{{ $novel->name }}">{{ $novel->name }}</a>
                </li>
                @endforeach
            </ul>
            <div class="pagination">
                @include('pagination.novel', ['paginator' => $novels])
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <!--/ truyen moi cap nhat -->
    </div>
@stop