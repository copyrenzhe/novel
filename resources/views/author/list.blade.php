@extends('app')
@section('content')
    <div id="left">
        <h1 class="title">{{ $name }}</h1>
        <h4 class="desc margin-less"></h4>
        <div class="l-category box">
            <ul class="content">
                @foreach($authors as $author)
                    <li>
                        <a class="c-title" href="{{ route('author', ['authorId' => $author->id]) }}" title="Destroyed">{{ $author->name }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="pagination">
                @include('pagination.novel', ['paginator' => $authors])
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