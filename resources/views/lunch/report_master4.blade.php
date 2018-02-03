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
        <h1>{{ $semester }} 教職各月請款資料</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.report_master4', 'method' => 'POST']) }}
                <input type="hidden" name="semester" value="{{ $semester }}">
                請先選擇餐期：{{ Form::select('order_id', $orders, $this_order_id, ['id' => 'order_id', 'class' => 'form-control', 'placeholder' => '請先選擇餐期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $mon }} 月教職請款資料</h4>
                </div>
                <div class="panel-content">
                <table class="table table-bordered">
                    <thead>
                    <tr class="bg-primary">
                        <th>項目</th><th>總餐數</th><th>單價</th><th>總計</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-info">
                        <td>合計</td><td>{{ $num }}</td><td>{{ $tea_money }}</td><td>{{ $num * $tea_money }}</td>
                    </tr>
                    </tbody>
                    <thead>
                    <tr class="bg-primary">
                        <td>姓名</td><td>餐數</td><td>單價</td><td>小計</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $total =0 ; ?>
                    @foreach($tea_order as $k => $v)
                    <tr>
                        <td>{{ $k }}</td><td>{{ $v }}</td><td>{{ $tea_money }}</td><td>{{ $v * $tea_money }}</td>
                    </tr>
                    <?php $total += $v; ?>
                    @endforeach
                    <tr class="bg-info">
                        <td>合計</td><td>{{ $total }}</td><td>{{ $tea_money }}</td><td>{{ $total * $tea_money }}</td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection