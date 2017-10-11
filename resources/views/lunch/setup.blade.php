@extends('layouts.master')

@section('page-title', '系統管理 | 午餐系統')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="">2.學生訂餐</a></li>
        <li><a href="">3.供餐確認表</a></li>
        <li><a href="">4.滿意度調查</a></li>
        <li><a href="">5.報表輸出</a></li>
        <li class="active"><a href="{{ route('lunch.setup') }}">6.系統管理</a></li>
    </ul>

@endsection