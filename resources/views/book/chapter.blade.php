@extends('app')
@section('title')
书虫网-{{ $chapter->novel->name }}-{{ $chapter->name }}无弹窗-{{ $chapter->novel->name }}最新章节
@stop
@section('content')
    @if(isset($js))
    <script>
        var shareData = {
            title: document.title,
            link: window.location.href,
            desc: document.title,
            imgUrl: "{{ env('APP_URL') . $chapter->novel->cover }}"
        };
        wx.onMenuShareTimeline(shareData);
        wx.onMenuShareAppMessage(shareData);
        wx.onMenuShareQQ(shareData);
        wx.onMenuShareWeibo(shareData);
        wx.onMenuShareQZone(shareData);
    </script>
    @endif
<div id="view-page">
    <!-- Thong tin truyen -->
    <h1 class="title"><a href="{{ route('book', ['bookId' => $chapter->novel_id]) }}" title="{{ $chapter->novel->name }}">{{ $chapter->novel->name }}</a> / {{ $chapter->name }}</h1>
    <input type="hidden" name="urlchange" value="{{ route('book', ['bookId' => $chapter->novel_id]) }}">
    <div class="tool-right">
        <a href="javascript:setbookmark();" title="Bookmark" class="btn">保存书签</a>
    </div>
    <div class="box">
        <div class="content">
            <h2 class="chapter-number">{{ $chapter->name }}</h2>
            <br class="clr">
            <div class="contents-comic">
                {!!  $chapter->content !!}
            </div>
        </div>
    </div>

    <!--/ thong tin truyen -->
    <script type="text/javascript">$(document).ready(function(){showImages(2);})</script>
    <br class="clr">
    <div class="chap-select">
        <div class="flr chap-select-dropdown">
            @if(isset($prev))
            <a href="{{ route('chapter', ['bookId' => $chapter->novel_id, 'chapterId' => $prev->id]) }}" class="btn-blue">上一章</a>
            @endif
            <a href="{{ route('book', ['bookId' => $chapter->novel_id]) }}" class="btn-blue">目录</a>
            @if(isset($next))
            <a href="{{ route('chapter', ['bookId' => $chapter->novel_id, 'chapterId' => $next->id]) }}" class="btn-blue">下一章</a>
            @endif
        </div>
    </div>
</div>
<script src="/dist/js/jstorage.min.js" type="text/javascript"></script>
<script>
    var book_id = "{{ $chapter->novel_id }}";
    var chapter_id = "{{ $chapter->id }}";
    var chapter_name = '{{ $chapter->name }}';
    $(function () {
        var chapterHistory = {
            'id' : chapter_id,
            'href' : window.location.href,
            'title' : chapter_name
        };
        $.jStorage.set(book_id, chapterHistory);
    })
</script>
@stop