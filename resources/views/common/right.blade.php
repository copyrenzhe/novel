<!-- right -->
<div id="right">
    <!-- menu-child -->
    <div class="menu-child">
        <h2 class="title">Genres</h2>
        <ul class="menu-child-ul">
            @foreach($genres as $genre)
                <li><a title="{{ route($genre->name) }}" href="/{{ $genre->name }}">{{ $genre->name }}</a></li>
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
            <li><a href="#thang" title="MONTH">MONTH</a></li>
        </ul>
        <div id="ngay" class="l-right box">
            <ul class="content">
                @foreach($dayPop as $item)
                    <li>
                        <div class="fll">
                            <a href="/book/{{ $item->id }}" class="crop" title="">
                                <img class="thumb-s" title="{{ $item->name }}" alt="{{ $item->name }}" src="{{ $item->cover }}" />
                            </a>
                        </div>
                        <div class="l-right-info">
                            <a class="r-title" href="/book/{{ $item->id }}" title="">{{ $item->name }}</a>
                            <a href="/author/{{ $item->author->id }}" title="{{ $item->author->name }}" class="e-user">{{ $item->author->name }}</a>
                            <span class="e-view">Views: 43870</span>
                        </div>
                    </li>
                @endforeach
                <li class="l-right-end">
                    <a rel="nofollow" href="#" class="box-more">查看更多</a>
                </li>
            </ul>
        </div>
        <div id="thang" class="l-right box" style="display: none;">
            <ul class="content">
                @foreach($monPop as $item)
                    <li>
                        <div class="fll">
                            <a href="/book/{{ $item->id }}" class="crop" title="">
                                <img class="thumb-s" title="{{ $item->name }}" alt="{{ $item->name }}" src="{{ $item->cover }}" />
                            </a>
                        </div>
                        <div class="l-right-info">
                            <a class="r-title" href="/book/{{ $item->id }}" title="">{{ $item->name }}</a>
                            <a href="/author/{{ $item->author->id }}" title="{{ $item->author->name }}" class="e-user">{{ $item->author->name }}</a>
                            <span class="e-view">Views: 43870</span>
                        </div>
                    </li>
                @endforeach
                <li class="l-right-end">
                    <a rel="nofollow" href="#" class="box-more">View more</a>
                </li>
            </ul>
        </div>
    </div>
    <!--/ top xem nhieu -->
</div>
<!--/ right -->