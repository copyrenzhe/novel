<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Match info</title>
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('/js/app.js')}}"></script>
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
    </div>
</body>
</html>