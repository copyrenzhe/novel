<!DOCTYPE html">
<html id="@yield('pagetype','')">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', '书虫网-无弹窗网络小说最新章节阅读网-shu000')</title>
    <meta name="keywords" content="@yield('keywords', '书虫网,无弹窗,网络小说,热门小说,最新章节,shu000')" />
    <meta name="description" content="@yield('description', '书虫网为广大网络书虫们免费提供了当前最火热的网络小说，拥有最佳的阅读体验，是广大网络小说爱好者们必不可少小说阅读网。')" />
    @yield('meta')
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="{{ elixir('dist/css/all.css') }}" />
    @yield('link')
    @if(isset($js))
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config({!!  $js->config([
                        'onMenuShareQQ',
                        'onMenuShareWeibo',
                        'onMenuShareTimeline',
                        'onMenuShareAppMessage'
                        ], config('app.debug')) !!});
    </script>
    @endif
</head>
<body>
    <div class="wrapper">
        @include('common.header')
        @include('common.navbar')
        <!-- main -->
        <div class="forshare" style="overflow:hidden;width:1px;height:1px;position:absolute;left:-100px;top:-100px;display:none;">
            <img src="/build/dist/images/logo.png" alt="">
        </div>
        @if( Session::has('flash_message') )
        <div class="alert">
            <h3>{{ Session::get('flash_message') }}</h3>
        </div>
        @endif

        <div id="main">
            @yield('content')
        </div>
        <!-- /main -->
        @include('common.footer')
    </div>
    <script type="text/javascript" src="{{ elixir('dist/js/all.js') }}"></script>
    <script type="text/javascript">
        var timestamp = Date.parse(new Date());
        var read_history = $.jStorage.get('history', []);

    </script>
    @yield('js')
</body>
</html>
