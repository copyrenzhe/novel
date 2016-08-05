@extends('app')
@section('content')
<div id="view-page">
    <!-- Thong tin truyen -->
    <h1 class="title"><a href="/book/{{ $chapter->novel->id }}" title="{{ $chapter->novel->name }}">{{ $chapter->novel->name }}</a> / {{ $chapter->name }}</h1>
    <input type="hidden" name="urlchange" value="/book/{{ $chapter->novel->id }}">
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
            <a href="/book/{{ $chapter->novel_id }}/{{ $prev->id }}" class="btn-blue">上一章</a>
            @endif
            <a href="/book/{{ $chapter->novel->id }}" class="btn-blue">目录</a>
            @if(isset($next))
            <a href="/book/{{ $chapter->novel_id }}/{{ $next->id }}" class="btn-blue">下一章</a>
            @endif
        </div>
    </div>
</div>
@stop