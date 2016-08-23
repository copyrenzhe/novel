@extends('app')
@section('content')
    <!--left-->
    <div id="left">
        <h1 class="title">Feed Back</h1>
        <div class="box register-page">
            <p style="font-size:14px;"></p><center></center><p></p>
            <div class="content">
                <form id="submit" method="post">
                    <p><label for="booktitle">书名</label><input id="booktitle" class="input-site" type="text" name="book_title" value=""></p>
                    <p><label for="bookurl">书链接</label><input id="bookurl" class="input-site" type="text" name="book_url" value=""></p>
                    <p><label for="name">您的昵称</label><input id="name" class="input-site" name="name" type="text" value=""></p>
                    <p><label for="email">您的邮箱</label><input id="email" class="input-site" type="text" name="email" value=""></p>
                    <p><label for="message">内容</label><br><textarea id="message" rows="4" cols="80" style="width: 606px; height: 130px; margin: 0px;" name="message" value=""> </textarea></p>
                    <input type="submit" value="提交" name="submit" class="btn-big">
                </form>
            </div>
        </div>
    </div>
    <!--/ left -->
    @include('common.right')
    <div class="clr"></div>
@stop