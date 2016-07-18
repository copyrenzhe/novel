@extends('app')
@section('content')
    <!-- left -->
    <div id="left">
        <!-- Truyen hot -->
        <h2 class="title">TOP NOVELS</h2>
        <div class="l-grid">
            <div class="e-wrapper">
                @foreach($topNovels as $novel)
                <div class="element">
                    <a href="/book/{{ $novel->id }}" class="crop" title=""><img class="thumb" src="{{ $novel->cover }}" border="0" alt="{{ $novel->name }}" /></a>
                    <div class="content">
                        <a class="e-title" href="/book/{{ $novel->id }}" title="{{ $novel->name }}" >{{ $novel->name }}</a>
                        <span class="e-view">Views: 3413</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!--/ Truyen hot -->
        <!-- truyen moi cap nhat -->
        <h1 class="title"><a href="/" title="Books online free" >BOOKS ONLINE FREE</a></h1>
        <div class="l-category box category-home">
            <ul class="content">
                @foreach($recentNovels as $novel)
                <li>
                    <a href="/author/{{ $novel->author->id }}" title="{{ $novel->author->name }}" class="cate-li-right">{{ $novel->author->name }}</a>
                    <a class="c-title" href="/book/{{ $novel->id }}" title="{{ $novel->name }}">{{ $novel->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        <!--/ truyen moi cap nhat -->
    </div>
    <!--/ left -->
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
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/241098/harry-potter-and-the-half-blood-prince.htm" class="crop" title=""><img class="thumb-s" title="Harry Potter and the Half-Blood Prince" alt="Harry Potter and the Half-Blood Prince" src="../uploads/truyen/Harry Potter and the Half-Blood Prince.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/241098/harry-potter-and-the-half-blood-prince.htm" title="">Harry Potter and the Half-Blood Prince</a>
                            <a href="http://booksonlinefree.net/author/jk-rowling-36.htm" title="J.K. Rowling" class="e-user">J.K. Rowling</a><span class="e-view">Views: 43870</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253521/captive-in-the-dark.htm" class="crop" title=""><img class="thumb-s" title="Captive in the Dark" alt="Captive in the Dark" src="/uploads/truyen/captive-in-the-dark.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253521/captive-in-the-dark.htm" title="">Captive in the Dark</a>
                            <a href="http://booksonlinefree.net/author/cj-roberts-1341.htm" title="C.J. Roberts" class="e-user">C.J. Roberts</a><span class="e-view">Views: 21433</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253685/where-she-went.htm" class="crop" title=""><img class="thumb-s" title="Where She Went" alt="Where She Went" src="/uploads/truyen/where-she-went1.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253685/where-she-went.htm" title="">Where She Went</a>
                            <a href="http://booksonlinefree.net/author/gayle-forman-1376.htm" title="Gayle Forman" class="e-user">Gayle Forman</a><span class="e-view">Views: 20543</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253663/the-boy-who-sneaks-in-my-bedroom-window.htm" class="crop" title=""><img class="thumb-s" title="The Boy Who Sneaks in My Bedroom Window" alt="The Boy Who Sneaks in My Bedroom Window" src="/uploads/truyen/the-boy-who-sneaks-in-my-bedroom-window.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253663/the-boy-who-sneaks-in-my-bedroom-window.htm" title="">The Boy Who Sneaks in My Bedroom Window</a>
                            <a href="http://booksonlinefree.net/author/kirsty-moseley-1373.htm" title="Kirsty Moseley" class="e-user">Kirsty Moseley</a><span class="e-view">Views: 18742</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253522/seduced-in-the-dark.htm" class="crop" title=""><img class="thumb-s" title="Seduced in the Dark" alt="Seduced in the Dark" src="/uploads/truyen/seduced-in-the-dark.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253522/seduced-in-the-dark.htm" title="">Seduced in the Dark</a>
                            <a href="http://booksonlinefree.net/author/cj-roberts-1341.htm" title="C.J. Roberts" class="e-user">C.J. Roberts</a><span class="e-view">Views: 17097</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253689/captivated-by-you.htm" class="crop" title=""><img class="thumb-s" title="Captivated by You" alt="Captivated by You" src="/uploads/truyen/captivated-by-you-read-online.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253689/captivated-by-you.htm" title="">Captivated by You</a>
                            <a href="http://booksonlinefree.net/author/-sylvia-day-172.htm" title=" Sylvia Day" class="e-user"> Sylvia Day</a><span class="e-view">Views: 17034</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/241097/harry-potter-and-the-order-of-the-phoenix.htm" class="crop" title=""><img class="thumb-s" title="Harry Potter and the Order of the Phoenix" alt="Harry Potter and the Order of the Phoenix" src="../uploads/truyen/Harry Potter and the Order of the Phoenix.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/241097/harry-potter-and-the-order-of-the-phoenix.htm" title="">Harry Potter and the Order of the Phoenix</a>
                            <a href="http://booksonlinefree.net/author/jk-rowling-36.htm" title="J.K. Rowling" class="e-user">J.K. Rowling</a><span class="e-view">Views: 16872</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/243276/perfect-chemistry.htm" class="crop" title=""><img class="thumb-s" title="Perfect Chemistry" alt="Perfect Chemistry" src="/uploads/truyen/Perfect-Chemistry.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/243276/perfect-chemistry.htm" title="">Perfect Chemistry</a>
                            <a href="http://booksonlinefree.net/author/simone-elkeles-278.htm" title="Simone Elkeles" class="e-user">Simone Elkeles</a><span class="e-view">Views: 13893</span>
                        </div>
                    </li>
                    <li class="l-right-end">
                        <a rel="nofollow" href="#" class="box-more">View more</a>
                    </li>
                </ul>
            </div>
            <div id="thang" class="l-right box" style="display: none;">
                <ul class="content">
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/241098/harry-potter-and-the-half-blood-prince.htm" class="crop" title=""><img class="thumb-s" title="Harry Potter and the Half-Blood Prince" alt="Harry Potter and the Half-Blood Prince" src="../uploads/truyen/Harry Potter and the Half-Blood Prince.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/241098/harry-potter-and-the-half-blood-prince.htm" title="">Harry Potter and the Half-Blood Prince</a>
                            <a href="http://booksonlinefree.net/author/jk-rowling-36.htm" title="J.K. Rowling" class="e-user">J.K. Rowling</a><span class="e-view">Views: 43870</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253521/captive-in-the-dark.htm" class="crop" title=""><img class="thumb-s" title="Captive in the Dark" alt="Captive in the Dark" src="/uploads/truyen/captive-in-the-dark.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253521/captive-in-the-dark.htm" title="">Captive in the Dark</a>
                            <a href="http://booksonlinefree.net/author/cj-roberts-1341.htm" title="C.J. Roberts" class="e-user">C.J. Roberts</a><span class="e-view">Views: 21433</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253685/where-she-went.htm" class="crop" title=""><img class="thumb-s" title="Where She Went" alt="Where She Went" src="/uploads/truyen/where-she-went1.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253685/where-she-went.htm" title="">Where She Went</a>
                            <a href="http://booksonlinefree.net/author/gayle-forman-1376.htm" title="Gayle Forman" class="e-user">Gayle Forman</a><span class="e-view">Views: 20543</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253663/the-boy-who-sneaks-in-my-bedroom-window.htm" class="crop" title=""><img class="thumb-s" title="The Boy Who Sneaks in My Bedroom Window" alt="The Boy Who Sneaks in My Bedroom Window" src="/uploads/truyen/the-boy-who-sneaks-in-my-bedroom-window.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253663/the-boy-who-sneaks-in-my-bedroom-window.htm" title="">The Boy Who Sneaks in My Bedroom Window</a>
                            <a href="http://booksonlinefree.net/author/kirsty-moseley-1373.htm" title="Kirsty Moseley" class="e-user">Kirsty Moseley</a><span class="e-view">Views: 18742</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253522/seduced-in-the-dark.htm" class="crop" title=""><img class="thumb-s" title="Seduced in the Dark" alt="Seduced in the Dark" src="/uploads/truyen/seduced-in-the-dark.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253522/seduced-in-the-dark.htm" title="">Seduced in the Dark</a>
                            <a href="http://booksonlinefree.net/author/cj-roberts-1341.htm" title="C.J. Roberts" class="e-user">C.J. Roberts</a><span class="e-view">Views: 17097</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/253689/captivated-by-you.htm" class="crop" title=""><img class="thumb-s" title="Captivated by You" alt="Captivated by You" src="/uploads/truyen/captivated-by-you-read-online.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/253689/captivated-by-you.htm" title="">Captivated by You</a>
                            <a href="http://booksonlinefree.net/author/-sylvia-day-172.htm" title=" Sylvia Day" class="e-user"> Sylvia Day</a><span class="e-view">Views: 17034</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/241097/harry-potter-and-the-order-of-the-phoenix.htm" class="crop" title=""><img class="thumb-s" title="Harry Potter and the Order of the Phoenix" alt="Harry Potter and the Order of the Phoenix" src="../uploads/truyen/Harry Potter and the Order of the Phoenix.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/241097/harry-potter-and-the-order-of-the-phoenix.htm" title="">Harry Potter and the Order of the Phoenix</a>
                            <a href="http://booksonlinefree.net/author/jk-rowling-36.htm" title="J.K. Rowling" class="e-user">J.K. Rowling</a><span class="e-view">Views: 16872</span>
                        </div>
                    </li>
                    <li>
                        <div class="fll"><a href="http://booksonlinefree.net/243276/perfect-chemistry.htm" class="crop" title=""><img class="thumb-s" title="Perfect Chemistry" alt="Perfect Chemistry" src="/uploads/truyen/Perfect-Chemistry.jpg" /></a></div>
                        <div class="l-right-info">
                            <a class="r-title" href="http://booksonlinefree.net/243276/perfect-chemistry.htm" title="">Perfect Chemistry</a>
                            <a href="http://booksonlinefree.net/author/simone-elkeles-278.htm" title="Simone Elkeles" class="e-user">Simone Elkeles</a><span class="e-view">Views: 13893</span>
                        </div>
                    </li>
                    <li class="l-right-end">
                        <a rel="nofollow" href="#" class="box-more">View more</a>
                    </li>
                </ul>
            </div>
        </div>
        <!--/ top xem nhieu -->
    </div>
    <!--/ right -->
    <div class="clr"></div>

@stop
