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
        <h1>{{ $semester }} 學生各月請款資料</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.report_master2', 'method' => 'POST']) }}
                <input type="hidden" name="semester" value="{{ $semester }}">
                請先選擇餐期：{{ Form::select('order_id', $orders, $this_order_id, ['id' => 'order_id', 'class' => 'form-control', 'placeholder' => '請先選擇餐期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $mon }} 月學生請款資料</h4>
                </div>
                <div class="panel-content">
                    <h2>結算統計表</h2>
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th>
                                月份
                            </th>
                            <th>
                                項目名稱
                            </th>
                            <th>
                                單價
                            </th>
                            <th>
                                全額補助餐數
                            </th>
                            <th>
                                全額補助金額
                            </th>
                            <th>
                                備註
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $last_year = 0;
                        $total_money = 0;
                        $total_num = 0;
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
                                {{ $v['a'] }}
                                <?php $total_num+= $v['a']; ?>
                            </td>
                            <td>
                                {{ $support_all_money*$v['a'] }}
                                <?php $total_money += $support_all_money*$v['a']; ?>
                            </td>
                            <td>

                            </td>
                        </tr>
                            <?php
                            $last_year = substr($k,0,1);
                            ?>
                        @endforeach
                        <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';" style="border-top-style: outset;border-color:#000000;">
                            <td>
                                合計
                            </td>
                            <td>

                            </td>
                            <td>
                                {{ $support_all_money }}
                            </td>
                            <td>
                                {{ $total_num }}
                            </td>
                            <td>
                                {{ $total_money }}
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