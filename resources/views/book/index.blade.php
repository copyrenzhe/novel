@extends('app')
@section('title')
{{ $novel->name }}-{{ $novel->name }}免费无弹窗-@if($novel->is_over){{ $novel->name }}全集-{{ $novel->name }}全文完整版 @else{{ $novel->name }}最新章节-{{ $novel->name }}最新章节列表@endif -书虫网 @stop
@section('keywords'){{ $novel->name }},@if($novel->is_over){{ $novel->name }}全文完整版,@else{{ $novel->name }}最新章节,@endif{{ $novel->author->name }}@stop
@section('description'){{ $novel->name }}@if($novel->is_over)已完成@else连载中@endif最新章节{{ $novel->chapter->last()->name }},作者{{ $novel->author->name }}的小说,{{ $novel->name }}全文免费阅读到书虫网(www.shu000.com)@stop
@section('meta')
<meta property="og:type" content="novel"/>
    <meta property="og:title" content="{{ $novel->name }}"/>
    <meta property="og:description" content="{{ preg_replace('/\s+/', '', strip_tags($novel->description)) }}"/>
    <meta property="og:image" content="{{ env('APP_URL') . $novel->cover }}"/>
    <meta property="og:novel:category" content="{{ category_maps()[$novel->type] }}"/>
    <meta property="og:novel:author" content="{{ $novel->author->name }}"/>
    <meta property="og:novel:book_name" content="{{ $novel->name }}"/>
    <meta property="og:novel:read_url" content="{{ route('book', ['bookId' => $novel->id]) }}"/>
    <meta property="og:url" content="{{ route('book', ['bookId' => $novel->id]) }}"/>
    <meta property="og:novel:status" content="{{ $novel->is_over ? '已完成' : '连载中' }}"/>
    <meta property="og:novel:author_link" content="{{ route('author', ['authorId' => $novel->author_id]) }}"/>
    <meta property="og:novel:update_time" content="{{ $novel->updated_at }}"/>
    <meta property="og:novel:latest_chapter_name" content="{{ $novel->chapter->last()->name }}"/>
    <meta property="og:novel:latest_chapter_url" content="{{ route('chapter', ['bookId' => $novel->id, 'chapterId' => $novel->chapter->last()->id]) }}"/>
    <meta property="og:novel:author_other_books" content="{{ $otherBooks }}"/>
@stop
@section('content')
    @if(isset($js))
    <script>
        var shareData = {
            title: document.title,
            link: window.location.href,
            desc: document.title,
            imgUrl: "{{ env('APP_URL') . $novel->cover }}"
        };
        wx.ready(function(){
            wx.onMenuShareTimeline(shareData);
            wx.onMenuShareAppMessage(shareData);
            wx.onMenuShareQQ(shareData);
            wx.onMenuShareWeibo(shareData);
            wx.onMenuShareQZone(shareData);
        })
    </script>
    @endif
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
                    @if($novel->cover)
                        <img src="{{ $novel->cover }}" border="0" title="{{ $novel->name }}" alt="{{ $novel->name }}" />
                    @else
                        <img src="{{ url('/cover/cover_default.jpg') }}" border="0" title="{{ $novel->name }}" alt="{{ $novel->name }}" >
                    @endif
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
                        <p>最新章节: <a id="readNew" href="" title=""></a></p>
                        <p>更新时间: {{ $novel->updated_at }}</p>
                        <p>上次看到: <a id="readLast" href="" title=""></a></p>
                    </div>
                    <div class="clr"></div>
                    <a id="readStart" href="" rel="nofollow" class="btn-big">开始阅读</a>
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
                            <li data-id="{{ $chapter->id }}"><p><a title="{{ $chapter->name }}" href="{{ route('chapter', ['bookId' => $novel->id, 'chapterId' => $chapter->id]) }}">{{ $chapter->name }}</a></p></li>
                            @endforeach
                        </ul>
                        <div class="clr"></div>
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
@section('js')
    <script src="{{ public_path('dist/js/jstorage.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
    var book_id = "{{ $novel->id }}";
    var $_pchapter = $("#_pchapter");
    $(function() {
        @if(isset($user))
        var user_id = "{{ $user->id }}";
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
        @endif

        //上次看到
        var chapterHistory = $.jStorage.get(book_id, null);
        var $firstLi = $_pchapter.find("li:eq(0)"),
            $lastLi = $_pchapter.find('li').last(),
            $readNew = $("#readNew"),
            $readStart = $("#readStart"),
            $readLast = $("#readLast");

        //最新章节
        var new_href = $lastLi.find('a').attr('href'),
            new_title = $lastLi.find('a').attr('title'),
            next_href = $firstLi.find('a').attr('href'),
            next_title = $firstLi.find('a').attr('title');
        $readNew.attr('href', new_href).attr('title', new_title).html(new_title);
        $readStart.attr('href', next_href).attr('title', next_title).html('开始阅读');
        if(chapterHistory){
            var last_title = chapterHistory['title'],
                    last_href = chapterHistory['href'],
                    last_id = chapterHistory['id'];
            $readLast.attr('href', last_href).attr('title', last_title).html(last_title);
            var $next = $("#_pchapter").find("li[data-id="+last_id+"]").next();
            next_href = $next.find('a').attr('href');
            next_title = $next.find('a').attr('title');
            if(next_href && next_title) {
                $readStart.attr('href', next_href).attr('title', next_title).html('继续阅读');
            }
        }
    });

    //旋转章节列表
    function revert() {
        $_pchapter.find("ul").toggleClass('dsort');
        $_pchapter.find("ul li").toggleClass('dsort');
    }

</script>
@stop
