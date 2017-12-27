@extends('layouts.master-no-hf')

@section('page-title', '午餐系統')

@section('content')
    <style>
        .table-bordered {
            border: 1px solid #ecf0f1 !important;
        }
        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > th,
        .table-bordered > tfoot > tr > th,
        .table-bordered > thead > tr > td,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > td {
            border: 1px solid #000000 !important;
        }
    </style>
<div class="container-fluid">
    <div class="page-header">
        <h1>{{ $semester }} 學生訂餐統計表(依身份)</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.report_stu2', 'method' => 'POST']) }}
                請選擇餐期：{{ Form::select('select_order_id', $lunch_orders, $lunch_order_id, ['id' => 'select_order_id', 'class' => 'form-control', 'placeholder' => '請選擇餐期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                <input type="hidden" name="semester" value="{{ $semester }}">
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>學生訂餐統計表(依身份)</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th rowspan="2">班級</th>
                            @foreach($this_order_dates as $k=>$v)
                                <th colspan="2">{{ substr($v,5,5) }}</th>
                            @endforeach
                        </tr>
                        <tr>
                        @foreach($this_order_dates as $k=>$v)
                            <th class="bg-danger">般</th><th class="bg-success">弱</th>
                        @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order_data as $k1=>$v1)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>{{ $k1 }}</td>
                                @foreach($this_order_dates as $k2=>$v2)
                                    <?php
                                    if(empty($v1[$v2]['g'])) $v1[$v2]['g'] = 0;
                                    if(empty($v1[$v2]['w'])) $v1[$v2]['w'] = 0;
                                    if(empty($g[$v2])) $g[$v2] = 0;
                                    if(empty($w[$v2])) $w[$v2] = 0;
                                    ?>
                                <td>{{ $v1[$v2]['g'] }}</td><td>{{ $v1[$v2]['w'] }}</td>
                                    <?php
                                        $g[$v2] += $v1[$v2]['g'];
                                        $w[$v2] += $v1[$v2]['w'];
                                    ?>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td>合計</td>
                            <?php $total_g = 0;$total_w=0; ?>
                            @foreach($this_order_dates as $k=>$v)
                                <th class="bg-danger">{{ $g[$v] }}</th><th class="bg-success">{{ $w[$v] }}</th>
                                <?php
                                    $total_g += $g[$v];
                                    $total_w += $w[$v];
                                ?>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                    本期一般生總餐數：{{ $total_g }}<br>
                    本期弱勢生總餐數：{{ $total_w }}<br>
                    本期學生總餐數：{{ $total_g+$total_w }}<br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection