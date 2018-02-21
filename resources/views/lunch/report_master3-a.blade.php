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
                                    <th>{{ substr($v,5,5) }}</th>
                                @endforeach
                                <th rowspan="2">當月用餐合計</th>
                                <th rowspan="2">應付金額合計</th>
                            </tr>
                            <tr>
                                @foreach($this_order_dates as $k=>$v)
                                    <th>
                                        {{ get_w($v) }}
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_people = 0;?>
                            @foreach($order_data as $k1=>$v1)
                                <tr bgcolor='#FFFFFF'>
                                    <td>{{ $k1 }}</td>
                                    <td>{{ $class_people[$k1] }}</td>
                                    <?php $total_people += $class_people[$k1]; ?>
                                    @foreach($this_order_dates as $k2=>$v2)
                                        <?php
                                        if(empty($v1[$v2]['a'])) $v1[$v2]['a'] = 0;
                                        if(empty($a[$v2])) $a[$v2] = 0;
                                        if(empty($class_a[$k1])) $class_a[$k1] = 0;
                                        ?>
                                        <td>{{ $v1[$v2]['a'] }}</td>
                                        <?php
                                        $a[$v2] += $v1[$v2]['a'];
                                        $class_a[$k1] += $v1[$v2]['a'];
                                        ?>
                                    @endforeach
                                    <td>{{ $class_a[$k1] }}</td>
                                    <td>{{ $class_a[$k1]  * $support_all_money }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>
                                    合計
                                </th>
                                <th>
                                    {{ $total_people }}
                                </th>
                                <?php $total_total=0; ?>
                                @foreach($this_order_dates as $k=>$v)
                                    <th>{{ $a[$v] }}</th>
                                    <?php $total_total+= $a[$v]; ?>
                                @endforeach
                                <th>{{ $total_total }}</th>
                                <th>{{ $total_total * $support_all_money}}</th>
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