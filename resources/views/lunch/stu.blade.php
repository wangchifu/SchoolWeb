@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1>學生訂餐</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li class="active"><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li><a href="">3.供餐確認表</a></li>
        <li><a href="">4.滿意度調查</a></li>
        <li><a href="">5.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">6.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-content">

                </div>
            </div>
        </div>
    </div>
@endsection
@include('layouts.partials.bootbox')