@extends('app')
@section('content')
    <!--left-->
    <div id="left">
        <!-- Thong tin truyen -->
        <h2 class="title">{{ $novel->name }}</h2>
        <div class="detail box">
            <div class="content">
                <div class="detail-thumb">
                    <img src="{{ $novel->cover }}" border="0" title="{{ $novel->name }}" alt="{{ $novel->name }}" />
                </div>
                <div class="detail-story">
                    <h1><a href="/book/{{ $novel->id }}" title="{{ $novel->name }}">{{ $novel->name }}</a></h1>
                    <div class="d-s-col">
                        <p>作者: <a href="/author/{{ $novel->author->id }}" title="{{ $novel->author->name }}">{{ $novel->author->name }}</a></p>
                        <p>分类: <a href="/{{ $novel->type }}" title="{{ $novel->type }}">{{ $genres[$novel->type] }}</a>
                        </p>
                        <p>热度: {{ $novel->hot }}</p>
                    </div>
                    <div class="d-s-col d-s-col-noright">
                        <p>最新章节: <a href="/book/{{ $novel->id }}/{{ $recentChapter->id }}" title="{{ $recentChapter->name }}">{{ $recentChapter->name }}</a></p>
                        <p>更新时间: {{ $novel->updated_at }}</p>
                        <p>上次看到: <a href="" title=""></a></p>
                    </div>
                    <div class="clr"></div>
                    <a href="/book/{{$novel->id}}/{{ $novel->chapter()->first()->id }}" rel="nofollow" class="btn-big">在线阅读</a>
                </div>
                <div class="clr"></div>
                <div class="desc-story" style="padding-top:10px;">
                    <strong>简介:</strong>
                    {!! $novel->description !!}
                </div>
            </div>
        </div>
        <!--/ thong tin truyen -->
        <!-- chap -->
        <h2 class="title mt10">章节列表:</h2>
        <div class="box search-chap">
            <div class="content">
                <div class="list-chap-wrap">
                    <div class="list-chap" id="_pchapter">
                        <ul>
                            @foreach($novel->chapter as $chapter)
                            <li><p><a title="{{ $chapter->name }}" href="/book/{{ $novel->id }}/{{ $chapter->id }}">{{ $chapter->name }}</a></p></li>
                            @endforeach
                        </ul>
                        <div class="clr"></div>
                        <script type="text/javascript">var cid	= '{{ $novel->id }}';ten = '{{ $novel->name }}';</script>
                    </div>
                </div>
            </div>
        </div>
        <!--/ chap -->

    </div>
    <!--/ left -->	 <!-- right -->
    @include('common.right')
    <div class="clr"></div>
@stop
