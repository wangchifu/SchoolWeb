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
            <h1>{{ $semester }} 學生供餐數量表(主計版)</h1>
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
                        <h4>學生供餐數量表(主計版)</h4>
                    </div>
                    <div class="panel-content">
                        供應廠商：　　　　　　　　　　　　　　　供餐日期：　　　　　　　　　　　　　　　廠商電話：　　　　　　　　　　　　　　　單價：新台幣 {{ $support_all_money }} 元
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th rowspan="3">班級</th>
                                <th rowspan="3">預訂<br>人數</th>
                                @foreach($this_order_dates as $k=>$v)
                                    <th colspan="2">{{ substr($v,5,5) }}</th>
                                @endforeach
                                <th rowspan="3">當月<br>用餐<br>合計</th>
                                <th rowspan="3">應付<br>金額<br>合計</th>
                                <th rowspan="3">全額<br>補助<br>餐數</th>
                                <th rowspan="3">部份<br>補助<br>餐數</th>
                            </tr>
                            <tr>
                                @foreach($this_order_dates as $k=>$v)
                                    <th colspan="2">
                                        {{ get_w($v) }}
                                    </th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($this_order_dates as $k=>$v)
                                    <th>般</th><th>弱</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_people = 0;$g=[];$w=[]; ?>
                            @foreach($order_data as $k1=>$v1)
                                <tr bgcolor='#FFFFFF'>
                                    <td>{{ $k1 }}</td>
                                    <td>{{ $class_people[$k1] }}</td>
                                    <?php $total_people += $class_people[$k1]; ?>
                                    @foreach($this_order_dates as $k2=>$v2)
                                        <?php
                                        if(empty($v1[$v2]['g'])) $v1[$v2]['g'] = 0;
                                        if(empty($v1[$v2]['w'])) $v1[$v2]['w'] = 0;
                                        if(empty($g[$v2])) $g[$v2] = 0;
                                        if(empty($w[$v2])) $w[$v2] = 0;
                                        if(empty($class_g[$k1])) $class_g[$k1] = 0;
                                        if(empty($class_w[$k1])) $class_w[$k1] = 0;
                                        ?>
                                        <td>{{ $v1[$v2]['g'] }}</td><td>{{ $v1[$v2]['w'] }}</td>
                                        <?php
                                        $g[$v2] += $v1[$v2]['g'];
                                        $w[$v2] += $v1[$v2]['w'];
                                        $class_g[$k1] += $v1[$v2]['g'];
                                        $class_w[$k1] += $v1[$v2]['w'];
                                        ?>
                                    @endforeach
                                    <td>{{ $class_g[$k1] + $class_w[$k1] }}</td>
                                    <td>{{ ($class_g[$k1] + $class_w[$k1]) * $support_all_money }}</td>
                                    <td>{{ $class_w[$k1] }}</td>
                                    <td>{{ $class_g[$k1] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th rowspan="2">數量總計</th>
                                <th rowspan="2">{{ $total_people }}</th>
                                <?php $total_g = 0;$total_w=0; ?>
                                @foreach($this_order_dates as $k=>$v)
                                    <th>{{ $g[$v] }}</th><th>{{ $w[$v] }}</th>
                                    <?php
                                    $total_g += $g[$v];
                                    $total_w += $w[$v];
                                    ?>
                                @endforeach
                                <th rowspan="2">{{ $total_g+$total_w }}</th>
                                <th rowspan="2">{{ ($total_g+$total_w) * $support_all_money}}</th>
                                <th rowspan="2">{{ $total_w }}</th>
                                <th rowspan="2">{{ $total_g }}</th>
                            </tr>
                            <tr>
                                <?php $total_gw = 0; ?>
                                @foreach($this_order_dates as $k=>$v)
                                    <th colspan="2">{{ $g[$v] + $w[$v] }}</th>
                                    <?php
                                    $total_gw += $g[$v]+$w[$v];

                                    ?>
                                @endforeach
                            </tr>
                            <tr>
                                <th>金額合計</th>
                                <th></th>
                                <?php $total_g = 0;$total_w=0; ?>
                                @foreach($this_order_dates as $k=>$v)
                                    <th colspan="2">{{ ($g[$v]+$w[$v]) * $support_all_money }}</th>
                                    <?php
                                    $total_g += $g[$v];
                                    $total_w += $w[$v];
                                    ?>
                                @endforeach
                                <th>{{ ($total_g+$total_w) * $support_all_money }}</th>
                                <th></th>
                                <th>{{ $total_w * $support_all_money}}</th>
                                <th>{{ $total_g * $support_all_money}}</th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    function get_w($d)
    {
        $arrDate=explode("-",$d);
        $week=date("w",mktime(0,0,0,$arrDate[1],$arrDate[2],$arrDate[0]));
        $cht_array =[
            '0'=>"星期日",
            '1'=>"星期一",
            '2'=>"星期二",
            '3'=>"星期三",
            '4'=>"星期四",
            '5'=>"星期五",
            '6'=>"星期六",
        ];
        return $cht_array[$week];
    }
    ?>
@endsection