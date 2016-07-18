<!-- header -->
<div id="header">
    <a class="menu-btn"></a><a class="panel-btn"></a>
    <a href="{{ url('/') }}" id="logo" title=""></a>
    <div id="search">
        <div class="search-wrap clearfix">
            <form action="{{ url('search') }}" method="GET">
                <input type="text" class="top_search" name="keyword">
                <input type="submit" title="search" value="search" class="top_search_submit sprites">
            </form>
        </div>
    </div>
</div>
<!-- /header -->
<!-- mobile search -->
<div class="mb-panel">
    <div class="mb-panel-wrap">
    </div>
</div>
<!-- / mobile search -->