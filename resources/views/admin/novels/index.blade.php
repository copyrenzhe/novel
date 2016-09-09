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
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>小说名</th>
                            <th>作者</th>
                            <th>类型</th>
                            <th>热度</th>
                            <th>章节数</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
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
            $('#novels').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ url('admin/novels/datatables') }}",
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'a_name', name: 'a_name'},
                    {data: 'type', name: 'type'},
                    {data: 'hot', name: 'hot'},
                    {data: 'chapter_num', name: 'chapter_num'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'operations', name: 'operations'}
                ]
            });
        });
    </script>
    <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
@endsection
