@extends('app')
@section('pagetype'){{"detail"}}@stop
@section('title'){{ $chapter->name }}-{{ $chapter->novel->name }}-书虫网@stop
@section('keywords'){{ $chapter->name }},@if($chapter->novel->is_over){{ $chapter->novel->name }}全文完整版,@else{{ $chapter->novel->name }}最新章节,@endif{{ $chapter->novel->name }}无弹窗,书虫网@stop
@section('description')小说{{ $chapter->novel->name }}正文 {{ $chapter->name }}在线阅读。作者{{ $chapter->novel->author->name }}的小说,{{ $chapter->novel->name }}全文免费阅读到书虫网(www.shu000.com)@stop
@section('link')<link rel="canonical" href="{{ route('chapter', ['bookId'=> $chapter->novel->id, 'chapterId' => $chapter->id]) }}" />@stop
@section('content')
    @if(isset($js))
    <script>
        var shareData = {
            title: document.title,
            link: window.location.href,
            desc: document.title,
            imgUrl: "{{ config('app.url') . $chapter->novel->cover }}"
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
                <br/>
                &nbsp;&nbsp;&nbsp;&nbsp;精采小说就在书虫网(www.shu000.com)
            </div>
        </div>
    </div>

    <!--/ thong tin truyen -->
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
@stop
@section('js')
<script>
    var book_id = "{{ $chapter->novel_id }}";
    var chapter_id = "{{ $chapter->id }}";
    var chapter_name = '{{ $chapter->name }}';
    var $content = $(".contents-comic");
    $(function () {
        var chapterHistory = {
            'id' : chapter_id,
            'href' : window.location.href,
            'title' : chapter_name
        };
        $.jStorage.set(book_id, chapterHistory);
        $content.html($content.html().replace(/<script[^>]*?>.*?<\/script>/, '').replace(/公告：笔趣阁APP上线了，支持安卓，苹果。请关注微信公众号进入下载安装 appxsyd \(按住三秒复制\)/, '').replace(/公告：本站推荐一款免费小说APP，告别一切广告。请关注微信公众号进入下载安装 appxsyd \(按住三秒复制\)/, ''));
    })
</script>
@stop
