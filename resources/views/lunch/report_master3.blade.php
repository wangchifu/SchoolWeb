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
<div class="container">
    <div class="page-header">
        <h1>{{ $semester }} 學生供餐數量表</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.report_master1', 'method' => 'POST']) }}
                <input type="hidden" name="semester" value="{{ $semester }}">
                請先選擇餐期：{{ Form::select('order_id', $orders, $this_order_id, ['id' => 'order_id', 'class' => 'form-control', 'placeholder' => '請先選擇餐期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $mon }} 月學生請款資料</h4>
                </div>
                <div class="panel-content">
                    <div class="well">
                        <table class="table table-bordered">
                            <tr>
                                <th class="col-md-6">
                                    項目
                                </th>
                                <th>
                                    金額
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    補助款計 (一般生餐數 x 部分補助金額 + 弱勢生餐數 x 全額補助金額)
                                </td>
                                <td>
                                    {{ $total_g }} x {{ $support_part_money }} + {{ $total_w }} x {{ $support_all_money }} = {{ $total_g * $support_part_money + $total_w * $support_all_money }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    自付款計 (一般生餐數 x 自付金額)
                                </td>
                                <td>
                                    {{ $total_g }} x {{ $stud_money }} = {{ $total_g * $stud_money }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    合計
                                </td>
                                <td>
                                    {{ $total_g * $support_part_money + $total_w * $support_all_money + $total_g * $stud_money }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th rowspan="2">
                                月份
                            </th>
                            <th rowspan="2">
                                項目名稱
                            </th>
                            <th rowspan="2">
                                單價
                            </th>
                            <th >
                                部分補助餐數
                            </th>
                            <th>
                                全額補助餐數
                            </th>
                            <th colspan="2">
                                部分補助金額
                            </th>
                            <th colspan="2">
                                自付金額
                            </th>
                            <th rowspan="2">
                                全額補助金額
                            </th>
                            <th rowspan="2">
                                合計
                            </th>
                            <th rowspan="2">
                                備註
                            </th>
                        </tr>
                        <tr class="bg-info">
                            <th colspan="2">
                                本月份用餐數
                            </th>
                            <th>
                                金額
                            </th>
                            <th>
                                小計
                            </th>
                            <th>
                                金額
                            </th>
                            <th>
                                小計
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_part = 0;
                        $total_self =0;
                        $total_all=0;
                        $total_class = 0;
                        $last_year = 0;
                        ?>
                        @foreach($class_data as $k => $v)
                            <?php
                            if($last_year != substr($k,0,1)){
                                $style = "style='border-top-style: outset;border-color:#000000;'";
                            }else{
                                $style = "";
                            }
                            ?>
                        <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';" {!! $style !!}>
                            <td>
                                {{ substr($mon,5,2) }}
                            </td>
                            <td>
                                {{ $k }}
                            </td>
                            <td>
                                {{ $support_all_money }}
                            </td>
                            <td>
                                {{ $v['g'] }}
                            </td>
                            <td>
                                {{ $v['w'] }}
                            </td>
                            <td>
                                {{ $support_part_money }}
                            </td>
                            <td>
                                {{ $v['g'] * $support_part_money }}
                            </td>
                            <td>
                                {{ $stud_money }}
                            </td>
                            <td>
                                {{ $v['g'] * $stud_money }}
                            </td>
                            <td>
                                {{ $v['w'] * $support_all_money }}
                            </td>
                            <td>
                                {{ $v['g'] * $support_part_money + $v['g'] * $stud_money + $v['w'] * $support_all_money }}
                            </td>
                            <?php
                                $total_part += $v['g'] * $support_part_money;
                                $total_self += $v['g'] * $stud_money;
                                $total_all += $v['w'] * $support_all_money;
                                $total_class += $v['g'] * $support_part_money + $v['g'] * $stud_money + $v['w'] * $support_all_money;
                                $last_year = substr($k,0,1);
                            ?>
                            <td>

                            </td>
                        </tr>
                        @endforeach
                        <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';" style="border-top-style: outset;border-color:#000000;">
                            <td>
                                合計
                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>
                                {{ $total_part }}
                            </td>
                            <td>

                            </td>
                            <td>
                                {{ $total_self }}
                            </td>
                            <td>
                                {{ $total_all }}
                            </td>
                            <td>
                                {{ $total_class }}
                            </td>
                            <td>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection