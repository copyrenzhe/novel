@extends('app')
@section('title')
{{ $novel->name }}无弹窗-
@if($novel->is_over)
    {{ $novel->name }}全集-{{ $novel->name }}全文完整版
@else
    {{ $novel->name }}最新章节-{{ $novel->name }}最新章节列表
@endif
-书虫网
@stop
@section('content')
    <style>
        .dsort{
            transform: rotate(180deg);
        }
    </style>
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
                    <h1><a href="{{ route('book', ['bookId' => $novel->id]) }}" title="{{ $novel->name }}">{{ $novel->name }}</a></h1>
                    <div class="d-s-col">
                        <p>作者: <a href="{{ route('author', ['authorId' => $novel->author->id ]) }}" title="{{ $novel->author->name }}">{{ $novel->author->name }}</a></p>
                        <p>分类: <a href="{{ route('category', ['category' => $novel->type])  }}" title="{{ $novel->type }}">{{ $genres[$novel->type] }}</a>
                        </p>
                        <p>热度: {{ $novel->hot }}</p>
                    </div>
                    <div class="d-s-col d-s-col-noright">
                        <p>最新章节: <a href="{{ route('chapter', ['bookId' => $novel->id, 'chapterId' => $recentChapter->id]) }} " title="{{ $recentChapter->name }}">{{ $recentChapter->name }}</a></p>
                        <p>更新时间: {{ $novel->updated_at }}</p>
                        <p>上次看到: <a href="" title=""></a></p>
                    </div>
                    <div class="clr"></div>
                    <a href="{{ route('chapter', ['bookId' => $novel->id, 'chapterId' => $novel->chapter->first()->id]) }}" rel="nofollow" class="btn-big">在线阅读</a>
                    @if(isset($user))
                    <a href="javascript:void(0);" rel="nofollow" class="btn-big subscribe">
                        @if(in_array($novel->id, $user->novel->pluck('id')->all()))
                            取消订阅
                        @else
                            订阅
                        @endif
                    </a>
                    @endif
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
        <h2 class="title mt10">章节列表 <a style="float: right;" href="javascript:revert();">倒序</a>:</h2>
        <div class="box search-chap">
            <div class="content">
                <div class="list-chap-wrap">
                    <div class="list-chap" id="_pchapter">
                        <ul>
                            @foreach($novel->chapter as $chapter)
                            <li><p><a title="{{ $chapter->name }}" href="{{ route('chapter', ['bookId' => $novel->id, 'chapterId' => $chapter->id]) }}">{{ $chapter->name }}</a></p></li>
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
    <script type="text/javascript">
        var book_id = {{ $novel->id }};
        @if(isset($user))
        var user_id = {{ $user->id }};
        $(function() {
            $('.subscribe').click(function() {
                $.ajax({
                    type: 'GET',
                    url: '/ajax/subscribe',
                    data: { book_id: book_id, user_id: user_id},
                    dataType: 'json',
                    success: function(data) {
                        if(data.isSubscribe==0){
                            alert('取消成功');
                            $('.subscribe').html('订阅');
                        } else {
                            alert('订阅成功');
                            $('.subscribe').html('取消订阅');
                        }
                    }
                })
            });
        });
        @endif
        //旋转章节列表
        function revert() {
            $("#_pchapter").find("ul").toggleClass('dsort');
            $("#_pchapter").find("ul li").toggleClass('dsort');
        }
    </script>
@stop
