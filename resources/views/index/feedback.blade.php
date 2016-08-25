@extends('app')
@section('content')
    <!--left-->
    <div id="left">
        <h1 class="title">意见反馈</h1>
        <div class="box register-page">
            <p style="font-size:14px;"></p>
            @include('common.errors')
            <p></p>
            <div class="content">
                {!! Form::open(['url' => 'feedback', 'method' => 'post']) !!}
                    <p>
                        {!! Form::label('title', '书名:') !!}
                        {!! Form::text('title', null, ['class' => 'input-site']) !!}
                    </p>
                    <p>
                        {!! Form::label('url', '书链接:') !!}
                        {!! Form::text('url', null, ['class' => 'input-site']) !!}
                    </p>
                    <p>
                        {!! Form::label('name', '您的昵称:') !!}
                        {!! Form::text('name', null, ['class' => 'input-site']) !!}
                    </p>
                    <p>
                        {!! Form::label('email', '您的邮箱:') !!}
                        {!! Form::text('email', null, ['class' => 'input-site']) !!}
                    </p>
                    <p>
                        {!! Form::label('content', '内容:') !!}
                        {!! Form::textarea('content', null, ['class' => 'input-site', 'style' => 'width: 606px; height: 130px; margin: 0px;']) !!}
                    </p>
                    {!! Form::submit('提交', ['class' => 'btn-big']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!--/ left -->
    @include('common.right')
    <div class="clr"></div>
@stop