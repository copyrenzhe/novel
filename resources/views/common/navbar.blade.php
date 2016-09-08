<!-- mobile navbar -->
<div class="mb-nav">
    <div class="mb-nav-wrap">
        <div class="mb-nav-search">
            <form action="{{ url('search') }}" method="get" enctype="multipart/form-data">
                <input class="top_search" id="searchInput_mobile" type="text" placeholder="输入小说名或作者" name="keyword" onkeydown="EnterKey(event);" />
                <input class="top_search_submit" type="submit" value="搜索" title="Search" />
            </form>
            <div class="clr"></div>
        </div>
        <a class="mb-nav-a" href="/" title="首页">首页</a>
        <a class="mb-nav-a" title="分类">分类</a>
        <div class="mb-nav-sub">
            @foreach($genres as $key => $genre)
            <a title="{{ $genre }}" href="{{ route('category', ['category' => $key]) }}">{{ $genre }}</a>
            @endforeach
        </div>
        <a class="mb-nav-a" href="{{ route('release') }}" title="最新">最新</a>
        <a class="mb-nav-a" href="{{ route('top') }}" title="排行">排行</a>
        <a class="mb-nav-a" href="{{ route('authors') }}" title="作者">作者</a>
        <a href="{{ route('over') }}" class="mb-nav-a" title="完结小说">完结</a>
    </div>
</div>
<!-- / mobile navbar -->
<!-- navbar -->
<div id="navbar">
    <ul class="top_nav">
        <li class="active"><a class="top_nav_home" href="/" title="首页"></a></li>
        <li><a title="Genres">小说分类</a>
            <div class="menu-expand">
                <ul class="menu-expand-ul">
                    @foreach($genres as $key => $genre)
                        <li><a title="{{ $genre }}" href="{{ route('category', ['category' => $key]) }}">{{ $genre }}</a></li>
                    @endforeach
                </ul>
            </div>
        </li>
        <li><a href="{{ route('release') }}" title="最新发布">最新发布</a></li>
        <li><a href="{{ route('top') }}" title="排行榜单">排行榜单</a></li>
        <li><a href="{{ route('authors') }}" title="作者大神">作者大神</a></li>
        <li><a href="{{ route('over') }}" title="完结小说">完结</a></li>
    </ul>
    <div class="nav_social"></div>
</div>
<!-- / navbar -->