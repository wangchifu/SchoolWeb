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
    <?php
    $m_tea = null;
    $g_tea = null;
    $m =null;
    $g = null;
    ?>
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
                        @foreach($order_data_tea as $k1=>$v1)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>{{ $k1 }}</td>
                                @foreach($this_order_dates as $k2=>$v2)
                                    <?php
                                    if(empty($v1[$v2]['m'])) $v1[$v2]['m'] = 0;
                                    if(empty($v1[$v2]['g'])) $v1[$v2]['g'] = 0;
                                    if(empty($m_tea[$v2])) $m_tea[$v2] = 0;
                                    if(empty($g_tea[$v2])) $g_tea[$v2] = 0;
                                    ?>
                                    <td>{{ $v1[$v2]['m'] }}</td><td>{{ $v1[$v2]['g'] }}</td>
                                    <?php
                                    $m_tea[$v2] += $v1[$v2]['m'];
                                    $g_tea[$v2] += $v1[$v2]['g'];
                                    ?>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td>合計</td>
                            <?php $total_m_tea = 0;$total_g_tea=0; ?>
                            @foreach($this_order_dates as $k=>$v)
                                <th class="bg-danger">{{ $m_tea[$v] }}</th><th class="bg-success">{{ $g_tea[$v] }}</th>
                                <?php
                                $total_m_tea += $m_tea[$v];
                                $total_g_tea += $g_tea[$v];
                                ?>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                    本期教職葷食總餐數：{{ $total_m_tea }}<br>
                    本期教職素食總餐數：{{ $total_g_tea }}<br>
                    本期教職總餐數：{{ $total_m_tea+$total_g_tea }}<br>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>二、學生訂餐統計表(依葷素)</h4>
                </div>
                <div class="panel-content">
                    <img src="{{ asset('img/plus.png') }}" width="16">：代表該班導師有訂餐！
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th rowspan="2" nowrap>班級</th>
                            @foreach($this_order_dates as $k=>$v)
                                <th colspan="2">{{ substr($v,5,5) }}</th>
                            @endforeach
                        </tr>
                        <tr>
                        @foreach($this_order_dates as $k=>$v)
                            <th class="bg-danger" width="100">葷</th><th class="bg-success">素</th>
                        @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order_data as $k1=>$v1)
                            @if($k1 == "201" or $k1 == "301" or $k1 == "401" or $k1 == "501" or $k1 == "601")
                            <tr class="bg-primary">
                                <th>班級</th>
                                @foreach($this_order_dates as $k=>$v)
                                    <th colspan="2">{{ substr($v,5,5) }}</th>
                                @endforeach
                            </tr>
                            @endif
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>{{ $k1 }}</td>
                                @foreach($this_order_dates as $k2=>$v2)
                                    <?php
                                    if(empty($v1[$v2]['m'])) $v1[$v2]['m'] = 0;
                                    if(empty($v1[$v2]['g'])) $v1[$v2]['g'] = 0;
                                    if(empty($order_data_tea[$k1][$v2]['m'])) $order_data_tea[$k1][$v2]['m']="";
                                    if(empty($order_data_tea[$k1][$v2]['g'])) $order_data_tea[$k1][$v2]['g']="";
                                    if(empty($m[$v2])) $m[$v2] = 0;
                                    if(empty($g[$v2])) $g[$v2] = 0;
                                    if(!empty($order_data_tea[$k1][$v2]['m'])){
                                        $tea_img_m = "<img src='" .asset('img/plus.png') ."' width='16'>";
                                    }else{
                                        $tea_img_m = "<img src='" .asset('img/no_color.png') ."' width='16'>";
                                    }
                                    if(!empty($order_data_tea[$k1][$v2]['g'])){
                                        $tea_img_g = "<img src='" .asset('img/plus.png') ."' width='16'>";                                    }else{
                                        $tea_img_g = "<img src='" .asset('img/no_color.png') ."' width='16'>";
                                    }

                                    if($stu_default[$k1]['m'] != $v1[$v2]['m']){
                                        $bg_m = "red";
                                        $text_m ="white";
                                    }else{
                                        $bg_m = "";
                                        $text_m ="";
                                    }

                                    if($stu_default[$k1]['g'] != $v1[$v2]['g']){
                                        $bg_g = "green";
                                        $text_g ="white";
                                    }else{
                                        $bg_g = "";
                                        $text_g ="";
                                    }

                                    ?>
                                <td bgcolor="{{ $bg_m }}" style="color:{{ $text_m }}" nowrap>{{ $v1[$v2]['m'] }}{!! $tea_img_m !!}</td><td bgcolor="{{ $bg_g }}" style="color:{{ $text_g }}">{{ $v1[$v2]['g'] }}{!! $tea_img_g !!}</td>
                                    <?php
                                        $m[$v2] += $v1[$v2]['m'];
                                        $g[$v2] += $v1[$v2]['g'];
                                    ?>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td>合計</td>
                            <?php $total_m = 0;$total_g=0;
                            ?>
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