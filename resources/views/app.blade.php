<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Novel</title>
    {{--<link href="{{ asset('/css/app.css') }}" rel="stylesheet">--}}
    {{--<script type="text/javascript" src="{{ asset('/js/app.js')}}"></script>--}}
    <script src="http://booksonlinefree.net/js/ipos.core.js" type="text/javascript"></script>
    <script src="http://booksonlinefree.net/js/ipos.comic.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="http://booksonlinefree.net/css/style.css" />
    <link rel="stylesheet" type="text/css" href="http://booksonlinefree.net/css/main.css" />
    <script type="text/javascript" src="http://booksonlinefree.net/js/santruyen.js"></script>
    <script type="text/javascript" src="http://booksonlinefree.net/js/tabs.js"></script>w

</head>
<body>
    <div class="wrapper">
        @include('common.header')
        @include('common.navbar')
        <!-- main -->
        <div id="main">
            @yield('content')
        </div>
        <!-- /main -->
        @include('common.footer')
    </div>
</body>
</html>