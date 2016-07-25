<!-- mobile navbar -->
<div class="mb-nav">
    <div class="mb-nav-wrap">
        <div class="mb-nav-search">
            <form action="/search.htm" method="post" enctype="multipart/form-data">
                <input class="top_search" id="searchInput_mobile" type="text" value="Search Title, Author" name="keyword" onkeydown="EnterKey(event);" />
                <input class="top_search_submit" type="submit" value="Search" title="Search" />
            </form>
            <div class="clr"></div>
        </div>
        <a class="mb-nav-a" href="/" title="Home">首页</a>
        <a class="mb-nav-a" title="Genres">分类</a>
        <div class="mb-nav-sub">
            @foreach($genres as $key => $genre)
            <a title="{{ $key }}" href="/{{ $key }}">{{ $genre }}</a>
            @endforeach
        </div>
        <a class="mb-nav-a" href="/new-releases.htm" title="New Releases">最新发布</a>
        <a class="mb-nav-a" href="/top-novel.htm" title="Top Novel">排行榜单</a>
        <a class="mb-nav-a" href="/authors.htm" title="New Releases">作者大神</a>
    </div>
</div>
<!-- / mobile navbar -->
<!-- navbar -->
<div id="navbar">
    <ul class="top_nav">
        <li class="active"><a class="top_nav_home" href="/" title="Home"></a></li>
        <li><a title="Genres">Genres</a>
            <div class="menu-expand">
                <ul class="menu-expand-ul">
                    @foreach($genres as $key => $genre)
                        <li><a title="{{ $genre }}" href="/{{ $key }}">{{ $genre }}</a></li>
                    @endforeach
                </ul>
            </div>
        </li>
        <li><a href="/new-releases.htm" title="New Releases">最新发布</a></li>
        <li><a href="/top-novel.htm" title="Hot Novel">排行榜单</a></li>
        <li><a href="/authors.htm" title="Authors">作者大神</a></li>
    </ul>
    <div class="nav_social"></div>
</div>
<!-- / navbar -->