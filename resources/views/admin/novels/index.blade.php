@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('/plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('htmlheader_title')
    Novels
@endsection

@section('contentheader_title')
    Novels list
@endsection

@section('main-content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">小说列表</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="novels" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>小说名</th>
                            <th>作者</th>
                            <th>类型</th>
                            <th>热度</th>
                            <th>章节数</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th>操作时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($novels as $novel)
                            <tr>
                                <td>{{ $novel->id }}</td>
                                <td>{{ $novel->name }}</td>
                                <td>{{ $novel->author->name }}</td>
                                <td>{{ $novel->type }}</td>
                                <td>{{ $novel->hot }}</td>
                                <td>{{ $novel->chapter_num }}</td>
                                <td>{{ $novel->created_at }}</td>
                                <td>{{ $novel->updated_at }}</td>
                                <td>
                                    <input type="text" class="btn btn-primary" value="更新">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
@section('script')
    <script>
        $(function () {
            $('#novels').DataTable();
        });
    </script>
    <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
@endsection
