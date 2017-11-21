@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1>報表輸出</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li><a href="">3.供餐確認表</a></li>
        <li><a href="">4.滿意度調查</a></li>
        <li><a href="{{ route('lunch.special') }}">5.特殊處理</a></li>
        <li class="active"><a href="{{ route('lunch.report') }}">6.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">7.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>教職相關</h4>
                </div>
                <div class="panel-content">
                    <a class="btn btn-success" href="" target="_blank">教職逐日訂餐表</a>
                    <a class="btn btn-success" href="" target="_blank">教職學期收費表</a>
                    <a class="btn btn-success" href="" target="_blank">教職費用收據單</a>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>學生相關</h4>
                </div>
                <div class="panel-content">

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>給主計</h4>
                </div>
                <div class="panel-content">

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>給廠商</h4>
                </div>
                <div class="panel-content">

                </div>
            </div>
        </div>
    </div>
@endsection
@include('layouts.partials.bootbox')