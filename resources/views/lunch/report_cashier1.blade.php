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
        <h1>{{ $semester }} 學生退費轉帳資料</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>產生「出納組」用學生退費轉帳資料</h4>
                </div>
                <div class="panel-content">
                <p>1.請下載學生帳戶資料範本</p>
                    <a class="btn btn-primary" href="{{ route('lunch.download_cashier_demo') }}"><span class="glyphicon glyphicon-download-alt"></span> 按此下載範本，填寫好存成csv檔</a>
                    <br><br>
                <p>2.請匯入上述學生帳戶資料</p>
                {{ Form::open(['route' => 'lunch.export_cashier', 'method' => 'POST','files'=>true]) }}
                <input name="csv" type="file" required="required" multiple>
                <input type="hidden" name="semester" value="{{ $semester }}">
                </div>
                <br>
                <button class="btn btn-success"><span class="glyphicon glyphicon-upload"></span> 匯入並下載給「出納組」退費轉帳資料</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection