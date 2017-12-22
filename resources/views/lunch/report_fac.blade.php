@extends('layouts.master-no-hf')

@section('page-title', '午餐系統')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1>{{ $semester }} 學生訂餐統計表(依葷素)-廠商版</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.report_fac', 'method' => 'POST']) }}
                請選擇餐期：{{ Form::select('select_order_id', $lunch_orders, $lunch_order_id, ['id' => 'select_order_id', 'class' => 'form-control', 'placeholder' => '請選擇餐期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                <input type="hidden" name="semester" value="{{ $semester }}">
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>一、教職訂餐統計表(依葷素)</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th rowspan="2">教職</th>
                            @foreach($this_order_dates as $k=>$v)
                                <th colspan="2">{{ substr($v,5,5) }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($this_order_dates as $k=>$v)
                                <th class="bg-danger">葷</th><th class="bg-success">素</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tea_order_data as $k1=>$v1)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>{{ $k1 }}</td>
                                @foreach($this_order_dates as $k2=>$v2)
                                    <?php
                                    if(empty($v1[$v2]['m'])) $v1[$v2]['m'] = 0;
                                    if(empty($v1[$v2]['g'])) $v1[$v2]['g'] = 0;
                                    if(empty($m[$v2])) $m[$v2] = 0;
                                    if(empty($g[$v2])) $g[$v2] = 0;
                                    ?>
                                    <td>{{ $v1[$v2]['m'] }}</td><td>{{ $v1[$v2]['g'] }}</td>
                                    <?php
                                    $m[$v2] += $v1[$v2]['m'];
                                    $g[$v2] += $v1[$v2]['g'];
                                    ?>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td>合計</td>
                            <?php $total_m = 0;$total_g=0; ?>
                            @foreach($this_order_dates as $k=>$v)
                                <th class="bg-danger">{{ $m[$v] }}</th><th class="bg-success">{{ $g[$v] }}</th>
                                <?php
                                $total_m += $m[$v];
                                $total_g += $g[$v];
                                ?>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                    本期教職葷食總餐數：{{ $total_m }}<br>
                    本期教職素食總餐數：{{ $total_g }}<br>
                    本期教職總餐數：{{ $total_m+$total_g }}<br>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>二、學生訂餐統計表(依葷素)</h4>
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
                            <th class="bg-danger">葷</th><th class="bg-success">素</th>
                        @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order_data as $k1=>$v1)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>{{ $k1 }}</td>
                                @foreach($this_order_dates as $k2=>$v2)
                                    <?php
                                    if(empty($v1[$v2]['m'])) $v1[$v2]['m'] = 0;
                                    if(empty($v1[$v2]['g'])) $v1[$v2]['g'] = 0;
                                    if(empty($m[$v2])) $m[$v2] = 0;
                                    if(empty($g[$v2])) $g[$v2] = 0;
                                    ?>
                                <td>{{ $v1[$v2]['m'] }}</td><td>{{ $v1[$v2]['g'] }}</td>
                                    <?php
                                        $m[$v2] += $v1[$v2]['m'];
                                        $g[$v2] += $v1[$v2]['g'];
                                    ?>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td>合計</td>
                            <?php $total_m = 0;$total_g=0; ?>
                            @foreach($this_order_dates as $k=>$v)
                                <th class="bg-danger">{{ $m[$v] }}</th><th class="bg-success">{{ $g[$v] }}</th>
                                <?php
                                    $total_m += $m[$v];
                                    $total_g += $g[$v];
                                ?>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                    本期學生葷食總餐數：{{ $total_m }}<br>
                    本期學生素食總餐數：{{ $total_g }}<br>
                    本期學生總餐數：{{ $total_m+$total_g }}<br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection