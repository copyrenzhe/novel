@extends('layouts.app')

@section('htmlheader_title')
    Home
@endsection

@section('contentheader_title')
    Dashboard
@endsection

@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="ion ion-ios-book-outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">小说总数</span>
                        <span class="info-box-number">{{ $count['novel']['all'] }}<small>本</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">用户总数</span>
                        <span class="info-box-number">{{ $count['user'] }}<small>人</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="ion ion-ios-browsers-outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">章节总数</span>
                        <span class="info-box-number">{{ $count['chapter'] }}<small>章</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">小说分布</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body" style="display: block;">
                        <canvas id="categoryChart" style="height: 444px; width: 888px;" height="444" width="888"></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('/plugins/fastclick/fastclick.min.js') }}"></script>
    <script>
        $(function(){
            var categoryChartCanvas = $("#categoryChart").get(0).getContext("2d");
            var categoryChart = new Chart(categoryChartCanvas);
            var PieData = [
                {
                    value: {{ $count['novel']['xuanhuan'] }},
                    color: "#f56954",
                    highlight: "#f56954",
                    label: "玄幻小说"
                },
                {
                    value: {{ $count['novel']['xiuzhen'] }},
                    color: "#00a65a",
                    lighlight: "#00a65a",
                    label: "修真小说"
                },
                {
                    value: {{ $count['novel']['dushi'] }},
                    color: "#f39c12",
                    highlight: "#f39c12",
                    label: "都市小说"
                },
                {
                    value: {{ $count['novel']['wangyou'] }},
                    color: "#00c0ef",
                    highlight: "#00c0ef",
                    label: "网游小说"
                },
                {
                    value: {{ $count['novel']['lishi'] }},
                    color: "#3c8dbc",
                    highlight: "#3c8dbc",
                    label: "历史小说"
                },
                {
                    value: {{ $count['novel']['kehuan'] }},
                    color: "#d2d6de",
                    highlight: "#d2d6de",
                    label: "科幻小说"
                },
                {
                    value: {{ $count['novel']['other'] }},
                    color: "#3b8bba",
                    highlight: "#3b8bba",
                    label: "其他小说"
                }
            ];
            var pieOptions = {
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 2,
                percentageInnerCutout: 50, // This is 0 for Pie charts
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false,
                responsive: true,
                maintainAspectRatio: true,
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
            };
            categoryChart.Doughnut(PieData, pieOptions);
        })
    </script>
@endsection
