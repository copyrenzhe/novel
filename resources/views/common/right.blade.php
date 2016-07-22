<!-- right -->
<div id="right">
    <!-- menu-child -->
    <div class="menu-child">
        <h2 class="title">Genres</h2>
        <ul class="menu-child-ul">
            @foreach($genres as $key => $genre)
                <li><a title="{{ $genre }}" href="/{{ $key }}">{{ $genre }}</a></li>
            @endforeach
        </ul>
        <div class="clr"></div>
    </div>
    <!--/ menu-child -->
    <!-- top xem nhieu -->
    <h2 class="title">MOST POPULAR</h2>
    <div class="right_tabs">
        <ul class="idTabs">
            <li><a class="selected" href="#ngay" title="DAY">DAY</a></li>
        </ul>
        <div id="ngay" class="l-right box">
            <ul class="content">
                @foreach($HotNovels as $item)
                    <li>
                        <div class="fll">
                            <a href="/book/{{ $item->id }}" class="crop" title="">
                                <img class="thumb-s" title="{{ $item->name }}" alt="{{ $item->name }}" src="{{ $item->cover }}" />
                            </a>
                        </div>
                        <div class="l-right-info">
                            <a class="r-title" href="/book/{{ $item->id }}" title="">{{ $item->name }}</a>
                            <a href="/author/{{ $item->author->id }}" title="{{ $item->author->name }}" class="e-user">{{ $item->author->name }}</a>
                            <span class="e-view">Views: {{ $item->hot }}</span>
                        </div>
                    </li>
                @endforeach
                <li class="l-right-end">
                    <a rel="nofollow" href="#" class="box-more">查看更多</a>
                </li>
            </ul>
        </div>
    </div>
    <!--/ top xem nhieu -->
</div>
<!--/ right -->