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
        <h1>{{ $semester }} 一般學生退費統計表</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>一般學生轉出退費統計表 (弱勢學生無退費問題)</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th class="col-md-1">
                                班級
                            </th>
                            <th class="col-md-1">
                                座號
                            </th>
                            <th class="col-md-1">
                                姓名
                            </th>
                            <th class="col-md-1">
                                退餐次數
                            </th>
                            <th class="col-md-1">
                                退費
                            </th>
                            <th>
                                退餐日期
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $total_out = 0; ?>
                        @foreach($out_data as $k=>$v)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>
                                    {{ substr($k,0,3) }}
                                </td>
                                <td>
                                    {{ substr($k,3,2) }}
                                </td>
                                <td>
                                    {{ $v['name'] }}
                                </td>
                                <td>
                                    {{ $v['times'] }}
                                </td>
                                <td>
                                    {{ $v['back_money'] }}
                                </td>
                                <td>
                                    {{ $v['dates'] }}
                                </td>
                                <?php $total_out += $v['back_money']; ?>
                            </tr>
                        @endforeach
                        <tr>
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
                                {{ $total_out }}
                            </td>
                            <td>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            {{ Form::open(['route'=>'lunch.report_stu3_print','method'=>'POST','target'=>'_blank']) }}
            <input type="hidden" name="semester" value="{{ $semester }}">
            <button class="btn btn-success">列印學生退費通知</button>
            {{ Form::close() }}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>一般學生請假退費統計表 (弱勢學生無退費問題)</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th>
                                班級
                            </th>
                            <th>
                                座號
                            </th>
                            <th>
                                姓名
                            </th>
                            <th>
                                退餐次數
                            </th>
                            <th>
                                退費
                            </th>
                            <th>
                                退餐日期
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $total = 0; ?>
                        @foreach($abs_data as $k=>$v)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>
                                    {{ substr($k,0,3) }}
                                </td>
                                <td>
                                    {{ substr($k,3,2) }}
                                </td>
                                <td>
                                    {{ $v['name'] }}
                                </td>
                                <td>
                                    {{ $v['times'] }}
                                </td>
                                <td>
                                    {{ $v['back_money'] }}
                                </td>
                                <td>
                                    {{ $v['dates'] }}
                                </td>
                                <?php $total += $v['back_money']; ?>
                            </tr>
                        @endforeach
                        <tr>
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
                                {{ $total }}
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